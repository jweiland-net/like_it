<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/like-it.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

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
