<?php
namespace MAB\CeLargegallery\Controller;

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

use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use MAB\CeLargegallery\Utility\FilesUtility;

/**
 * Helper plugin for the ce_largegallery content element.
 */
class GalleryController extends ActionController
{
    /**
     * action xhr
     * This action should be called via XHR from the content element 'ce_largegallery'.
     *
     * @param int $offset
     * @param string $key
     *
     * @return string JSON
     */
    public function xhrAction($offset, $key)
    {
        $jsonData = [
            "offset" => 0,
            "start" => 0,
            "end" => 0,
            "total" => 0,
            "content" => ""
        ];

        // get storage- and folder-identifier
        list($storageIdentifier, $folderIdentifier) = explode(":", \MAB\CeLargegallery\Utility\EncryptionUtility::decrypt($key));

        $imagesOnAjaxLoad = (int) $this->settings["imagesOnAjaxLoad"] > 0 ? (int) $this->settings["imagesOnAjaxLoad"] : 12;
        $fileExtensionFilter = $this->settings["fileExtensionFilter"] ? $this->settings["fileExtensionFilter"] : "jpg";

        // get the images and count data
        $filesUtility = $this->objectManager->get(FilesUtility::class);
        try {
            $filesData = $filesUtility->getFiles($storageIdentifier, $folderIdentifier, $offset, $imagesOnAjaxLoad, $fileExtensionFilter);
        } catch (\Exception $e) {
            $jsonData["content"] = "Fehler beim Laden der Bilder: " . $e->getMessage();
            return json_encode($jsonData);
        }

        // fill json data
        $this->view->assignMultiple([
            "images" => $filesData["images"],
            "total" => $filesData["total"]
        ]);

        $jsonData["offset"] = $filesData["nextOffset"];
        $jsonData["start"] = $filesData["start"];
        $jsonData["end"] = $filesData["end"];
        $jsonData["total"] = $filesData["total"];
        $jsonData["content"] = $this->view->render();
        
        return json_encode($jsonData);
    }
}
