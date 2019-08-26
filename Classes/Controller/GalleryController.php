<?php
namespace MAB\CeLargegallery\Controller;

use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use MAB\CeLargegallery\Utility\FilesUtility;

/**
 *
 */
class GalleryController extends ActionController
{
    /**
     * Undocumented variable
     *
     * @var array
     */
    protected $jsonData = [
        "offset" => 0,
        "start" => 0,
        "end" => 0,
        "total" => 0,
        "content" => ""
    ];

    /**
     * action ajaxList
     *
     * @param int $offset
     * @param string $key
     *
     * @return void
     */
    public function xhrAction($offset, $key)
    {
        $folderCredentials = \MAB\CeLargegallery\Utility\EncryptionUtility::decrypt($key);

        $folderInfo = explode(":", $folderCredentials);

        $filesUtility = $this->objectManager->get(FilesUtility::class);

        try {
            $filesData = $filesUtility->getFiles($folderInfo[0], $folderInfo[1], $offset);
        } catch (\Exception $e) {
            return json_encode($this->jsonData);
        }

        $this->view->assignMultiple([
            "images" => $filesData["images"],
            "total" => $filesData["total"]
        ]);

        $this->jsonData["offset"] = $filesData["nextOffset"];
        $this->jsonData["start"] = $filesData["start"];
        $this->jsonData["end"] = $filesData["end"];
        $this->jsonData["total"] = $filesData["total"];
        $this->jsonData["content"] = $this->view->render();
        
        return json_encode($this->jsonData);
    }
}
