<?php

return [
    'ctrl' => [
        'label' => 'uid',
        'tstamp' => 'tstamp',
        'title' => 'LLL:EXT:like_it/Resources/Private/Language/locallang_db.xlf:tx_likeit_like.title',
        'crdate' => 'crdate',
        'security' => [
            'ignorePageTypeRestriction' => true
        ],
        'typeicon_classes' => [
            'default' => 'tx-likeit-thumbsup',
        ],
        'searchFields' => '',
    ],
    'columns' => [
        'liked_uid' => [
            'label' => 'LLL:EXT:like_it/Resources/Private/Language/locallang_db.xlf:tx_likeit_like.liked_uid',
            'config' => [
                'type' => 'input',
                'size' => 8,
                'max' => 80,
                'eval' => 'num',
                'required' => true,
            ],
        ],
        'liked_table' => [
            'label' => 'LLL:EXT:like_it/Resources/Private/Language/locallang_db.xlf:tx_likeit_like.liked_table',
            'config' => [
                'type' => 'input',
                'size' => 20,
                'max' => 80,
                'eval' => 'trim',
                'required' => true,
            ],
        ],
        'cookie_value' => [
            'label' => 'LLL:EXT:like_it/Resources/Private/Language/locallang_db.xlf:tx_likeit_like.cookie_value',
            'config' => [
                'type' => 'password',
                'size' => 20,
                'hashed' => false,
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
