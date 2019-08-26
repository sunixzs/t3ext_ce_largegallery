<?php
namespace MAB\CeLargegallery\Utility;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Resource\ResourceFactory;
use TYPO3\CMS\Core\Resource\Filter\FileExtensionFilter;
use TYPO3\CMS\Core\Resource\Folder;

class FilesUtility
{
    /**
     * File extensions to load from folder and subfolders
     * @var string
     */
    const FILE_EXTENSION_LIST = "jpg,jpeg";

    /**
     * Amount of images each load
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
            [
                $filter, 'filterFileList'
            ]
        ]);
        
        // get all images
        $files = $folder->getFiles(0, 0, Folder::FILTER_MODE_USE_OWN_FILTERS, true, true, "name");

        // set numbers
        $data["total"] = count($files);
        $data["start"] = $offset + 1;

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
