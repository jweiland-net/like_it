<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/like-it.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

use TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider;

return [
    'tx-likeit-thumbsup' => [
        'provider' => SvgIconProvider::class,
        'source' => 'EXT:like_it/Resources/Public/Icons/thumbs-up-solid.svg',
    ],
];
