<?php

/*
 * This file is part of the package jweiland/like-it.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

return [
    'ctrl' => [
        'label' => 'uid',
        'tstamp' => 'tstamp',
        'title' => 'LLL:EXT:like_it/Resources/Private/Language/locallang_db.xlf:tx_likeit_like.title',
        'crdate' => 'crdate',
        'typeicon_classes' => [
            'default' => 'tx-likeit-thumbsup',
        ],
        'security' => [
            'ignorePageTypeRestriction' => true,
        ],
        'searchFields' => '',
    ],
    'columns' => [
        'liked_uid' => [
            'label' => 'LLL:EXT:like_it/Resources/Private/Language/locallang_db.xlf:tx_likeit_like.liked_uid',
            'config' => [
                'type' => 'number',
                'format' => 'integer',
                'required' => true,
            ],
        ],
        'liked_table' => [
            'label' => 'LLL:EXT:like_it/Resources/Private/Language/locallang_db.xlf:tx_likeit_like.liked_table',
            'config' => [
                'type' => 'input',
                'size' => 20,
                'max' => 80,
                'required' => true,
                'eval' => 'trim',
            ],
        ],
        'cookie_value' => [
            'label' => 'LLL:EXT:like_it/Resources/Private/Language/locallang_db.xlf:tx_likeit_like.cookie_value',
            'config' => [
                'type' => 'password',
                'required' => true,
            ],
        ],
    ],
    'types' => [
        '1' => [
            'showitem' => '
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
                    liked_uid,liked_table,cookie_value
            ',
        ],
    ],
];
