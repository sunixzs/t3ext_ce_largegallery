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
     * Loads $amountOfImages images from a folder.
     *
     * @param int $storageIdentifier
     * @param string $folderIdentifier
     * @param int $offset
     * @param int $amountOfImages
     * @param string $fileExtensionFilter
     * @return array
     */
    public function getFiles($storageIdentifier, $folderIdentifier, $offset, $amountOfImages = 12, $fileExtensionFilter = "jpg")
    {
        $data = [
            "total" => 0,
            "start" => 0,
            "end" => 0,
            "images" => [],
            "nextOffset" => $offset + $amountOfImages
        ];

        $resourceFactory = ResourceFactory::getInstance();

        // get the storage
        $storage = $resourceFactory->getStorageObject($storageIdentifier);

        // get the folder
        $folder = $storage->getFolder($folderIdentifier);

        // apply filter to folder
        $filter = GeneralUtility::makeInstance(FileExtensionFilter::class);
        $filter->setAllowedFileExtensions($fileExtensionFilter);
        $folder->setFileAndFolderNameFilters([
            [ $filter, "filterFileList" ]
        ]);
        
        // get all images for total count
        $files = $folder->getFiles(0, 0, Folder::FILTER_MODE_USE_OWN_FILTERS, true, true, "name");

        // set numbers
        $data["total"] = count($files);
        $data["start"] = $offset + 1;

        // slice out the visible images
        $files = array_slice($files, $offset, $amountOfImages);
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
