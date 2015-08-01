<?php

require __DIR__.'/config_with_app.php'; 

// länka in theme
$app->theme->configure(ANAX_APP_PATH . 'config/theme_wgtotw.php');

// Visa grid om jag vill
if( $app->request->getGet('show-grid') !== NULL) {
    $app->theme->addStylesheet('css/show-grid.css');
}

// Använd clean länkar
$app->url->setUrlType(\Anax\Url\CUrl::URL_CLEAN);

// Länka in navbar
$app->navbar->configure(ANAX_APP_PATH . 'config/navbar_wgtotw.php');

// create database connection
$di->setShared('db', function() {
    $db = new \Mos\Database\CDatabaseBasic();
    $db->setOptions(require ANAX_APP_PATH . 'config/database_sqlite.php');
    $db->connect();
    return $db;
});


// create a flash messenger service
$di->setShared('flasher', function () use ($di) {
    $Flasher = new \Joah\Flash\CFlashSession($di);
    $Flasher->setDI($di);
    return $Flasher;
});


// Me 
$app->router->add('', function() use ($app) {
    $app->theme->setTitle("Hem och trädgård");

    $content = $app->fileContent->get('forum/content.md');
    $content = $app->textFilter->doFilter($content, 'shortcode, markdown');
    
    $app->views->add('forum/content', [
        'content' => $content,
    ]);
    
    
    $content = $app->fileContent->get('forum/sidebar.md');
    $content = $app->textFilter->doFilter($content, 'shortcode, markdown');
    
    
    $app->views->add('forum/content', [
        'content' => $content,
    ], 'sidebar');
    
});


// Report 
$app->router->add('report', function() use ($app) {
    $app->theme->setTitle("Redovisning");

    $content = $app->fileContent->get('report.md');
    $content = $app->textFilter->doFilter($content, 'shortcode, markdown');
    
    
    $byline = $app->fileContent->get('byline.md');
    $byline = $app->textFilter->doFilter($byline, 'shortcode, markdown');
 
    $app->views->add('me/page', [
        'content' => $content,
        'byline' => $byline, 
    ]);    
});


// Route to show welcome to dice
$app->router->add('dice', function() use ($app) {

    $app->views->add('dice/index');
    $app->theme->setTitle("Roll a dice");

});


// Route to roll dice and show results
$app->router->add('dice/roll', function() use ($app) {

    // Add extra assets
    $app->theme->addStylesheet('css/dice.css');

    // Check how many rolls to do
    $roll = $app->request->getGet('roll', 1);
    $app->validate->check($roll, ['int', 'range' => [1, 100]])
        or die("Roll out of bounds");

    // Make roll and prepare reply
    $dice = new \Mos\Dice\CDice();
    $dice->roll($roll);

    $app->views->add('dice/index', [
        'roll'      => $dice->getNumOfRolls(),
        'results'   => $dice->getResults(),
        'total'     => $dice->getTotal(),
    ]);

    $app->theme->setTitle("Rolled a dice");
    
    // testing to implement a comment page inside something else
    $app->dispatcher->forward([
        'controller' => 'comment',
        'action' => 'index',
        'params' => [3],
    ]);
});


// Source 
$app->router->add('source', function() use ($app) {
 
    $app->theme->addStylesheet('css/source.css');
    $app->theme->setTitle("Källkod");
 
    $source = new \Mos\Source\CSource([
        'secure_dir' => '..', 
        'base_dir' => '..', 
        'add_ignore' => ['.htaccess'],
    ]);
 
    $app->views->add('me/source', [
        'content' => $source->View(),
    ]);
 
}); 


// comments 

// create comment controller
$di->set('CommentController', function() use ($di) {
    //$controller = new Phpmvc\Comment\CommentController();
    //$controller = new Joah\MyComment\MyCommentController(); // made my own extended class
    $controller = new Joah\Comment\CommentController(); // new version with database
    $controller->setDI($di);
    return $controller;
});


// users
// Create usercontroller
$di->set('UsersController', function() use ($di) {
    $controller = new \Anax\Users\UsersController();
    $controller->setDI($di);
    return $controller;
});



// flash testing route
$app->router->add('flash', function() use ($app) {

    $app->theme->setTitle("Test flash messages");
    $app->theme->addStylesheet('../vendor/joah/flash-messenger/webroot/css/flash.css');
    
    $title = "Examples of Flash Message use";

    $content = "";
    $content .= $app->flasher->success('Hello world!');
    $content .= $app->flasher->notice('Hola mundo!');
    $content .= $app->flasher->warning('Bonjour monde!');
    $content .= $app->flasher->error('Hallo Welt!');
    
    
    $app->views->add('default/page', [
        'title' => $title,
        'content' => $content,
    ]);
});



$app->router->handle();
$app->theme->render();
