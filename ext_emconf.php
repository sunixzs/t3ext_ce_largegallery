<?php
$EM_CONF[ $_EXTKEY ] = [
    'title' => 'Largegallery',
    'description' => 'Provides a content element where you can choose a folder with images to present them in frontend.',
    'category' => 'frontend',
    'version' => '1.0.4',
    'state' => 'alpha',
    'uploadfolder' => false,
    'modify_tables' => '',
    'clearcacheonload' => true,
    'author' => 'Marcel Briefs',
    'author_email' => 'mb@lbrmedia.de',
    'constraints' => [
        'depends' => [
            'typo3' => '9.0.0-9.99.99',
        ],
        'conflicts' => [ ],
        'suggests' => [ ]
    ]
];