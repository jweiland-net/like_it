<?php
if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_likeit_like');

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
    'LikeIt',
    'web',
    'like_statistics',
    '',
    [
        \JWeiland\LikeIt\Controller\LikeModuleController::class => 'list',
    ],
    [
        'access' => 'user,group',
        'icon' => 'EXT:like_it/Resources/Public/Icons/Extension.svg',
        'labels' => 'LLL:EXT:like_it/Resources/Private/Language/locallang_db.xlf:module.like_statistics.label',
        'navigationComponentId' => null,
        'inheritNavigationComponentFromMainModule' => false,
    ]
);
