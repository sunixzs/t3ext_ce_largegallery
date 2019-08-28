<?php
namespace MAB\CeLargegallery\DataProcessing;

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

use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\ContentObject\DataProcessorInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use MAB\CeLargegallery\Utility\FilesUtility;
use MAB\CeLargegallery\Utility\EncryptionUtility;
use TYPO3\CMS\Core\Service\FlexFormService;

/**
 * Class for data processing for the content element "ce_largegallery"
 */
class FolderImagesProcessor implements DataProcessorInterface
{
    /**
     * Process data for the content element "ce_largegallery"
     *
     * @param ContentObjectRenderer $cObj The data of the content element or page
     * @param array $contentObjectConfiguration The configuration of Content Object
     * @param array $processorConfiguration The configuration of this processor
     * @param array $processedData Key/value store of processed data (e.g. to be passed to a Fluid View)
     * @return array the processed data as key/value store
     */
    public function process(
        ContentObjectRenderer $cObj,
        array $contentObjectConfiguration,
        array $processorConfiguration,
        array $processedData
    ) {
        $processedData["images"] = [];

        if (!isset($processedData["data"]["pi_flexform"]) || $processedData["data"]["pi_flexform"] === "") {
            return $processedData;
        }
        

        // parse flexform and get storage- and folder-identifier
        $flexformService = GeneralUtility::makeInstance(FlexFormService::class);
        $flexformData = $flexformService->convertFlexFormContentToArray($cObj->data["pi_flexform"]);
        if (!isset($flexformData["settings"]["folder"])) {
            return $processedData;
        }

        list($storageIdenfifier, $folderIdentifier) = explode(":", $flexformData["settings"]["folder"]);

        $imagesOnPageLoad = (int) $processorConfiguration["imagesOnPageLoad"] > 0 ? (int) $processorConfiguration["imagesOnPageLoad"] : 12;
        $fileExtensionFilter = $processorConfiguration["fileExtensionFilter"] ? $processorConfiguration["fileExtensionFilter"] : "jpg";

        // get the images and count data
        $filesUtility = GeneralUtility::makeInstance(FilesUtility::class);
        try {
            $processedData = array_merge($processedData, $filesUtility->getFiles($storageIdenfifier, $folderIdentifier, 0, $imagesOnPageLoad, $fileExtensionFilter));
        } catch (\Exception $e) {
            $processedData["errorMessage"] = $e->getMessage();
        }

        // to load the next images via XHR we need to submit the folder to the extbase plugin
        // disguise it to make it hard for the public world to load images from other folders in storage than the one set in flexform
        $processedData["encrypted_storage_credentials"] = \MAB\CeLargegallery\Utility\EncryptionUtility::encrypt($flexformData["settings"]["folder"]);
        
        return $processedData;
    }
}
