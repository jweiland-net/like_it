<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/like-it.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\LikeIt\Utility;

use JWeiland\LikeIt\Exception\CouldNotSetCookieException;
use TYPO3\CMS\Core\Utility\StringUtility;

class CookieUtility
{
    public const COOKIE_NAME = 'tx_likeit';

    /**
     * Get cookie value for like cookie
     *
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
