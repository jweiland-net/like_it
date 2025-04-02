<?php
declare(strict_types=1);

use JWeiland\LikeIt\Controller\LikeModuleController;

return [
    'web_LikeItLikeStatistics' => [
        'parent' => 'web',
        'position' => 'bottom',
        'access' => 'user',
        'icon' => 'EXT:like_it/Resources/Public/Icons/Extension.svg',
        'labels' => 'LLL:EXT:like_it/Resources/Private/Language/locallang_db.xlf:module.like_statistics.label',
        'inheritNavigationComponentFromMainModule' => false,
        'path' => '/module/web/LikeItLikeStatistics',
        'extensionName' => 'LikeIt',
        'controllerActions' => [
            LikeModuleController::class => [
                'list',
            ],
        ],
    ],
];
