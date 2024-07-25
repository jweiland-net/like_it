<?php
defined('TYPO3') or die();

call_user_func(static function () {
    $GLOBALS['TYPO3_CONF_VARS']['FE']['eID_include']['tx_likeit_like']
        = \JWeiland\LikeIt\Ajax\LikeController::class . '::processRequest';
});
