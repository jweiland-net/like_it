<?php

/*
 * This file is part of the package jweiland/like-it.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

$EM_CONF[$_EXTKEY] = [
    'title' => 'Like it',
    'description' => 'Like nearly any frontend content thanks to a custom view helper. No login required.',
    'category' => 'fe',
    'author' => 'Stefan Froemken',
    'author_email' => 'support@jweiland.net',
    'author_company' => 'jweiland.net',
    'state' => 'stable',
    'version' => '3.0.0',
    'constraints' => [
        'depends' => [
            'typo3' => '12.4.40-12.4.99',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
];
