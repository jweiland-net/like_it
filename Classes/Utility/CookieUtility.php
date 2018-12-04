<?php
declare(strict_types=1);
namespace JWeiland\LikeIt\Utility;

/*
 * This file is part of the like_it project.
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

use JWeiland\LikeIt\Exception\CouldNotSetCookieException;
use TYPO3\CMS\Core\Utility\StringUtility;

/**
 * Class CookieUtility
 */
class CookieUtility
{
    public const COOKIE_NAME = 'tx_likeit';

    /**
     * Get cookie value for like cookie
     *
     * @return string
     * @throws CouldNotSetCookieException
     */
    public static function getCookieValue(): string
    {
        if (isset($_COOKIE[self::COOKIE_NAME]) && $_COOKIE[self::COOKIE_NAME]) {
            $cookieValue = trim($_COOKIE[self::COOKIE_NAME]);
        } else {
            $cookieValue = StringUtility::getUniqueId();
            if (!setcookie(self::COOKIE_NAME, $cookieValue, 2147483647)) {
                throw new CouldNotSetCookieException(
                    'Could net set a cookie named ' . self::COOKIE_NAME . '!',
                    1543419469332
                );
            }
        }
        return $cookieValue;
    }
}
