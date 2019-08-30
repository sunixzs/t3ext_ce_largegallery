<?php
namespace MAB\CeLargegallery\Hooks\PageLayoutView;

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

use \TYPO3\CMS\Backend\View\PageLayoutViewDrawItemHookInterface;
use \TYPO3\CMS\Backend\View\PageLayoutView;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Service\FlexFormService;

/**
 * Contains a preview rendering for the page module of CType="ce_largegallery"
 */
class GeneralPreviewRenderer implements PageLayoutViewDrawItemHookInterface
{

    /**
     * Preprocesses the preview rendering of a content element of type "My new content element"
     *
     * @param \TYPO3\CMS\Backend\View\PageLayoutView $parentObject Calling parent object
     * @param bool $drawItem Whether to draw the item using the default functionality
     * @param string $headerContent Header content
     * @param string $itemContent Item content
     * @param array $row Record row of tt_content
     *
     * @return void
     */
    public function preProcess(
        PageLayoutView &$parentObject,
        &$drawItem,
        &$headerContent,
        &$itemContent,
        array &$row
    ) {
        if ($row['CType'] === 'ce_largegallery') {
            if ($row['pi_flexform']) {
                // parse flexform
                $flexformService = GeneralUtility::makeInstance(FlexFormService::class);
                $data = $flexformService->convertFlexFormContentToArray($row['pi_flexform']);
                $content = $data["settings"]["folder"] ? "Ordner: " . $data["settings"]["folder"] : "-- Kein Ordner angegeben! --";
                if ($data["settings"]["imagesOnPageLoad"] && (int) $data["settings"]["imagesOnPageLoad"] > 0) {
                    $content .= '<br />Anzahl Bilder beim Laden der Seite: ' . (int) $data["settings"]["imagesOnPageLoad"];
                }
                if ($data["settings"]["imagesOnAjaxLoad"] && (int) $data["settings"]["imagesOnAjaxLoad"] > 0) {
                    $content .= '<br />Anzahl Bilder bei weiteren Ladevorgängen: ' . (int) $data["settings"]["imagesOnAjaxLoad"];
                }
                $itemContent .= $parentObject->linkEditContent($content, $row);
            }

            $drawItem = false;
        }
    }
}
