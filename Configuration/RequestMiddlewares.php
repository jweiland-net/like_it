<?php

declare(strict_types=1);

return [
    'frontend' => [
        'jweiland/like-it' => [
            'target' => \JWeiland\LikeIt\Middleware\LikeMiddleware::class,
            'before' => [
                'typo3/cms-frontend/tsfe',
            ],
            'after' => [
                'typo3/cms-frontend/page-resolver',
            ],
        ],
    ],
];
