<?php
/**
 * Config-file for Anax, theme related settings, return it all as array.
 *
 */
return [

    /**
     * Settings for Which theme to use, theme directory is found by path and name.
     *
     * path: where is the base path to the theme directory, end with a slash.
     * name: name of the theme is mapped to a directory right below the path.
     */
    'settings' => [
        'path' => ANAX_INSTALL_PATH . 'theme/',
        'name' => 'anax-wgtotw',
    ],

    
    /** 
     * Add default views.
     */
    'views' => [
        // header
        [
            'region'   => 'header', 
            'template' => 'forum/header', 
            'data'     => [
                'siteTitle' => "Allt om hem och trädgård",
                'siteTagline' => "WGTOTW - Allt om hem och trädgård",
            ], 
            'sort'     => -1
        ],
        // footer
        ['region' => 'footer', 'template' => 'forum/footer', 'data' => [], 'sort' => -1],

        // navbar
        [
        'region' => 'navbar', 
        'template' => [
            'callback' => function() {
                return $this->di->navbar->create();
            },
        ], 
        'data' => [], 
        'sort' => -1
        ],
    ],
    
        

    /** 
     * Data to extract and send as variables to the main template file.
     */
    'data' => [

        // Language for this page.
        'lang' => 'sv',

        // Append this value to each <title>
        'title_append' => ' | WGTOTW',

        // Stylesheets
        'stylesheets' => ['css/anax-grid/style.php'],
        //'stylesheets' => ['css/style.css', 'css/navbar_wgtotw.css'],

        // Inline style
        'style' => null,

        // Favicon
        'favicon' => 'favicon.ico',

        // Path to modernizr or null to disable
        'modernizr' => 'js/modernizr.js',

        // Path to jquery or null to disable
        'jquery' => '//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js',

        // Array with javscript-files to include
        'javascript_include' => [],

        // Use google analytics for tracking, set key or null to disable
        'google_analytics' => null,
    ],
];

