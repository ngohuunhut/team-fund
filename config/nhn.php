<?php

return [
    'sheet_id' => env('SHEET_ID'),
    'folder' => env('BANK_FOLDER', 'Banks/Sacom'),
    'members' => [
        'from' => env('RANGE_FROM', 'B6'),
        'to' => env('RANGE_TO', 'B17'),
        'sheet_name' => env('SHEET_NAME', 'Thu_2024'),
    ],
    'months' => [
        1 => "C",
        2 => "D",
        3 => "E",
        4 => "F",
        5 => "G",
        6 => "H",
        7 => "I",
        8 => "J",
        9 => "K",
        10 => "L",
        11 => "M",
        12 => "N",
    ],
];
