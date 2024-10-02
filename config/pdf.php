<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Font Path
    |--------------------------------------------------------------------------
    |
    | The directory where your font files are located. This path should be
    | accessible from your application.
    |
    */
    'font_path' => base_path('public/fonts/'),

    /*
    |--------------------------------------------------------------------------
    | Font Data
    |--------------------------------------------------------------------------
    |
    | This array holds the information for each font you want to use in your PDFs.
    | The key is the font family name that you will reference in your HTML or CSS,
    | and the value is the filename of the font file located in the 'font_path' directory.
    |
    */
    'font_data' => [
        'Droid-Naskh-Regular' => 'Droid-Naskh-Regular.ttf',
        // Add more fonts as needed
    ],

    /*
    |--------------------------------------------------------------------------
    | Other Options
    |--------------------------------------------------------------------------
    |
    | Add any other options you need for PDF generation.
    |
    */
    'other_options' => [
        'embedFont' => true,
        // Add more options here
    ],
];
