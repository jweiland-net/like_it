<?php

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
                'type' => 'input',
                'size' => 8,
                'max' => 80,
                'eval' => 'required,num',
            ],
        ],
        'liked_table' => [
            'label' => 'LLL:EXT:like_it/Resources/Private/Language/locallang_db.xlf:tx_likeit_like.liked_table',
            'config' => [
                'type' => 'input',
                'size' => 20,
                'max' => 80,
                'eval' => 'required,trim',
            ],
        ],
        'cookie_value' => [
            'label' => 'LLL:EXT:like_it/Resources/Private/Language/locallang_db.xlf:tx_likeit_like.cookie_value',
            'config' => [
                'type' => 'input',
                'size' => 20,
                'max' => 255,
                'eval' => 'required,trim,password',
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
