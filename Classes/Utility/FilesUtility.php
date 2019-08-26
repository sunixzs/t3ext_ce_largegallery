<?php
namespace MAB\CeLargegallery\Utility;

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Resource\ResourceFactory;
use TYPO3\CMS\Core\Resource\Filter\FileExtensionFilter;
use TYPO3\CMS\Core\Resource\Folder;

class FilesUtility
{
    /**
     * Filter for file extensions to load from folder and subfolders.
     * @var string
     */
    const FILE_EXTENSION_LIST = "jpg,jpeg";

    /**
     * Amount of images each load.
     * @var int
     */
    const MAX_ITEMS = 12;

    /**
     * Loads self::MAX_ITEMS images from a folder.
     *
     * @param int $storageIdentifier
     * @param string $folderIdentifier
     * @param int $offset
     * @return array
     */
    public function getFiles($storageIdentifier, $folderIdentifier, $offset)
    {
        $data = [
            "total" => 0,
            "start" => 0,
            "end" => 0,
            "images" => [],
            "nextOffset" => $offset + self::MAX_ITEMS
        ];

        $resourceFactory = ResourceFactory::getInstance();

        // get the storage
        try {
            $storage = $resourceFactory->getStorageObject($storageIdentifier);
        } catch (\Exception $e) {
            return $data;
        }

        // get the folder
        try {
            $folder = $storage->getFolder($folderIdentifier);
        } catch (\Exception $e) {
            return $data;
        }

        // apply filter to folder
        $filter = GeneralUtility::makeInstance(FileExtensionFilter::class);
        $filter->setAllowedFileExtensions(self::FILE_EXTENSION_LIST);
        $folder->setFileAndFolderNameFilters([
            [ $filter, "filterFileList" ]
        ]);
        
        // get all images for total count
        $files = $folder->getFiles(0, 0, Folder::FILTER_MODE_USE_OWN_FILTERS, true, true, "name");

        // set numbers
        $data["total"] = count($files);
        $data["start"] = $offset + 1;

        // slice out the visible images
        $files = array_slice($files, $offset, self::MAX_ITEMS);
        $i = $offset;
        foreach ($files as $file) {
            $i++;
            $data["images"][] = [
                "num" => $i,
                "file" => $file
            ];
        }

        $data["end"] = $offset + count($data["images"]);

        return $data;
    }
}
