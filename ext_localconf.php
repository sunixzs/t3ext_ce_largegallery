<?php
if (! defined('TYPO3_MODE')) {
    die('Access denied.');
}

/**
 * Register hooks for tt_content_drawItem
 */
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['cms/layout/class.tx_cms_layout.php']['tt_content_drawItem']['ce_largegallery'] = \MAB\CeLargegallery\Hooks\PageLayoutView\GeneralPreviewRenderer::class;

/**
 * Add type icon
 */
$iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconRegistry::class);
$iconRegistry->registerIcon(
    'ce_largegallery',
    \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
    [
        'source' => 'EXT:ce_largegallery/Resources/Public/gfx/TCA/ce_largegallery.svg'
    ]
);

/**
 * Add Plugin to load further images with XHR
 */
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'MAB.CeLargegallery',
    'Pi1',
    [
        'Gallery' => 'xhr'
    ]
);