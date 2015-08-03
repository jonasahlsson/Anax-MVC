<?php
/**
 * Config-file for navigation bar.
 *
 */
return [

    // Use for styling the menu
    'class' => 'navbar',
 
    // Here comes the menu strcture
    'items' => [

        // This is a menu item
        'start'  => [
            'text'  => 'Förstasida',
            'url'   => $this->di->get('url')->create(''),
            'title' => 'Förstasida'
        ],
 
        // This is a menu item
        'questions'  => [
            'text' => 'Frågor',
            'url'   => $this->di->get('url')->create('forum'),
            'title' => 'Frågor',
            
            // Here we add the submenu, with some menu items, as part of a existing menu item
            'submenu' => [

                'items' => [

                    // This is a menu item of the submenu
                    'question-overview'  => [
                        'text'  => 'Översikt',
                        'url'   => $this->di->get('url')->create('forum/overview-question'),
                        'title' => 'Url as internal route within this frontcontroller'
                    ],

                    // This is a menu item of the submenu
                    'question-create'  => [
                        'text'  => 'Skapa ny',
                        'url'   => $this->di->get('url')->asset('forum/new-question'),
                        'title' => 'Skapa en ny fråga',
                    ],
                ],
            ],

        ],
 
        // This is a menu item
        'tags' => [
            'text'  =>'Taggar',
            'url'   => $this->di->get('url')->create('tag'),
            'title' => 'Taggar',
        ],

        // This is a menu item
        'user' => [
            'text'  =>'Användare',
            'url'   => $this->di->get('url')->create('users'),
            'title' => 'Användare',
        ],

        // This is a menu item
        'about' => [
            'text'  =>'Om oss',
            'url'   => $this->di->get('url')->create('about'),
            'title' => 'Om oss',
        ],
    ],


    /**
     * Callback tracing the current selected menu item base on scriptname
     *
     */
    'callback' => function ($url) {
        if ($url == $this->di->get('request')->getCurrentUrl(false)) {
            return true;
        }
    },



    /**
     * Callback to check if current page is a decendant of the menuitem, this check applies for those
     * menuitems that has the setting 'mark-if-parent' set to true.
     *
     */
    'is_parent' => function ($parent) {
        $route = $this->di->get('request')->getRoute();
        return !substr_compare($parent, $route, 0, strlen($parent));
    },



   /**
     * Callback to create the url, if needed, else comment out.
     *
     */
   /*
    'create_url' => function ($url) {
        return $this->di->get('url')->create($url);
    },
    */
];
