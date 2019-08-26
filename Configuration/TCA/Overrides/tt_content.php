<?php
/**
 * Add and configure the content element ce_largegallery.
 */
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPlugin(
    [
        "Große Bildergallerie",
        "ce_largegallery",
        "EXT:ce_largegallery/Resources/Public/Icons/ce_largegallery.svg"
    ],
    "CType",
    "ce_largegallery"
);

// ... add flexform
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue("*", "FILE:EXT:ce_largegallery/Configuration/FlexForms/FolderSelect.xml", "ce_largegallery");

// ... configure TCA
$GLOBALS["TCA"]["tt_content"]["types"]["ce_largegallery"] = [
    "showitem" => implode(",", [
        "--div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general",
        "--palette--;;general",
        "header;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:header.ALT.html_formlabel",
        "pi_flexform",
        "--div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.appearance",
        "--palette--;;frames",
        "--palette--;;appearanceLinks",
        "--div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:language",
        "--palette--;;language",
        "--div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access",
        "--palette--;;hidden",
        "--palette--;;access",
        "--div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:categories",
        "--div--;LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:sys_category.tabs.category",
        "categories","--div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:notes",
        "rowDescription",
        "--div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:extended"
    ])
];

// ... icon in backend page view
$GLOBALS["TCA"]["tt_content"]["ctrl"]["typeicon_classes"]["ce_largegallery"] = "ce_largegallery";
