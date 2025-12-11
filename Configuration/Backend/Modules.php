<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/like-it.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

return [
    'web_like_statistics' => [
        'parent' => 'web',
        'access' => 'user,group',
        'workspaces' => 'live',
        'labels' => 'LLL:EXT:like_it/Resources/Private/Language/locallang_db.xlf:module.like_statistics.label',
        'extensionName' => 'LikeIt',
        'icon' => 'EXT:like_it/Resources/Public/Icons/Extension.svg',
        'controllerActions' => [
            \JWeiland\LikeIt\Controller\LikeModuleController::class => 'list',
        ],
    ],
];
