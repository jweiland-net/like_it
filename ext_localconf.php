<?php
if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

call_user_func(static function () {
    $GLOBALS['TYPO3_CONF_VARS']['FE']['eID_include']['tx_likeit_like']
        = \JWeiland\LikeIt\Ajax\LikeController::class . '::processRequest';

    $iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconRegistry::class);
    $iconRegistry->registerIcon(
        'tx-likeit-thumbsup',
        \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
        [
            'source' => 'EXT:like_it/Resources/Public/Icons/thumbs-up-solid.svg',
        ]
    );
});
