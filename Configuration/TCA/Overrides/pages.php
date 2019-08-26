<?php
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

/**
 * Register static Page TSconfig files...
 */

ExtensionManagementUtility::registerPageTSConfigFile(
    "ce_largegallery",
    "Configuration/TsConfig/Contentelements.typoscript",
    "Contentelement Largegallery"
);
