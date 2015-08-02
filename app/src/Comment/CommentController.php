<?php

namespace Joah\Comment;

class CommentController implements \Anax\DI\IInjectionAware
{
    use \Anax\DI\TInjectable,
        \Anax\MVC\TRedirectHelpers;
    
    protected $comment;
    
    /**
     * Initialize the controller.
     *
     * @return void
     */
    public function initialize()
    {
        // load comment model
        $this->comment = new \Joah\Comment\Comment();
        $this->comment->setDI($this->di);
        
        // activate session
        $this->di->session(); // Will load the session service which also starts the session

        // load CForm 
        $this->di->set('form', '\Mos\HTMLForm\CForm');
    }

    //set up database connection and add some example posts
    public function setupAction() {
        
        $content = $this->fileContent->get('comment/setup.md');
    
        $content = $this->textFilter->doFilter($content, 'shortcode, markdown');
        
        $this->views->add('comment/content', [
        'content' => $content,
        ]);
        
        $this->theme->setTitle('Initiera tabell comment');
        //$this->db->setVerbose(); 
     
        $this->db->dropTableIfExists('comment')->execute();
     
        $this->db->createTable(
            'comment',
            [
                'id' => ['integer', 'primary key', 'not null', 'auto_increment'],
                'name' => ['varchar(80)'],
                'mail' => ['varchar(80)'],
                'web' => ['varchar(80)'],
                'content' => ['text'],
                'timestamp' => ['datetime'],
                'page' => ['integer'],
            ]
        )->execute();

        
        $this->db->insert(
            'comment',
            ['name', 'mail', 'web',  'content', 'timestamp', 'page']
        );
     
        //$now = gmdate('Y-m-d H:i:s');
        
        //Exempel post 1 på page 1
        $this->db->execute([
            'testnamn',
            'test@example.com',
            'www.example.com',
            'Det här är en kommentarstext',
            time(),
            1
        ]);
        
        //Exempel post 2 på page 1
        $this->db->execute([
            'testnamn2',
            'test2@example.com',
            'www.example.com',
            'Det här är nästa kommentarstext',
            time(),
            1
        ]);
        
        //Exempel post 2 på page 1
        $this->db->execute([
            'testnamn3',
            'test3@example.com',
            'www.example.com',
            'Nu är page2 invigd',
            time(),
            2
        ]);
    }
    
    
    // display comments for a page & form for new comment
    public function displayAction($page, $add = null)
    {   
        //set default timezone to get rid of error warning
        date_default_timezone_set('Europe/Stockholm');
        
        $this->theme->setTitle("Kommentera");
        $this->views->add('comment/index');
        $this->printAction($page);
        
        if(isset($add)) {
            $this->addAction($page);
        }
    }
    
    
    // default page is no.1
    public function indexAction($page = 1, $add = null)
    {        
        $this->displayAction($page, $add);
    }


    /**
     * Print comments.
     * 
     * @return void
     */
    public function printAction($page)
    {
        $comments = $this->comment;
        
        // fetch comments
        $all = $comments->findAll();

        //$this->views->add('comment/print-comments-database', [
        $this->views->add('comment/print-comments-database-gravatar', [
            'comments' => $all,
            'page' => $page,
        ]);
        
    }
    
    
    /**
     * Add a comment.
     *
     * @return void
     */
    public function addAction($page)
    {

        $form = $this->di->form->create([], [
            'name' => [
                'type'        => 'text',
                'label'       => 'Namn:',
                'required'    => true,
                'validation'  => ['not_empty'],
            ],
            'mail' => [
                'type'        => 'text',
                'label'       => 'E-post:',
                'required'    => true,
                'validation'  => ['email_adress'],
            ],
            'web' => [
                'type'        => 'text',
                'label'       => 'Web:',
                'required'    => false,
            ],
            'content' => [
                'type'        => 'textarea',
                'label'       => 'Kommentarstext:',
                'required'    => true,
                'validation'  => ['not_empty'],
            ],
            'page' => [
                'type'        => 'hidden',
                'label'       => 'page',
                'value'       => $page
            ],
            'spara' => [
                'type'      => 'submit',
                'callback'  => function ($form) {
                    $res = $this->comment->save([
                    'name' => $form->Value('name'),
                    'mail' => $form->Value('mail'),
                    'web' => $form->Value('web'),
                    'content' => $form->Value('content'),
                    'page' => $form->Value('page'),
                    'timestamp' => time(),
                    ]);

                    //$form->saveInSession = true;
                    return $res;
                }
            ],

        ]);
        
        // Check the status of the form
        $status = $form->check();
     
        // if form was submitted
        if ($status === true) {
            //$form->AddOUtput("<p><i>Sparat!</i></p>");
            $this->redirectTo("comment/index/{$form->Value('page')}");
        } 
        // If form could not be submitted.
        else if ($status === false) {
            // What to do when form could not be processed?
            $form->AddOutput("<p><i>Något gick fel.</i></p>");
            
            $form->saveInSession = true;
            
            $this->redirectTo();
        }
        
        // Print form
        $this->di->views->add('comment/page', [
        'title' => "Skriv din egna kommentar!",
        'content' => $form->getHTML()
    ]);

    }

    
    
    public function editAction($id)
    {
        //$this->db->setVerbose(); 
        // fetch comment
        $comment = $this->comment->find($id);
        
        //alert if no such commentId
        $output = empty($comment) ? "Hittar ingen kommentar med id = $id" : null; 
        
        $form = $this->di->form->create([], [
            'name' => [
                'type'        => 'text',
                'label'       => 'Namn:',
                'value'       => $comment->name,
                'required'    => true,
                'validation'  => ['not_empty'],
            ],
            'mail' => [
                'type'        => 'text',
                'label'       => 'E-post:',
                'value'       => $comment->mail,    
                'required'    => true,
                'validation'  => ['email_adress'],
            ],
            'web' => [
                'type'        => 'text',
                'label'       => 'Web:',
                'value'       => $comment->web,
                'required'    => false,
            ],
            'content' => [
                'type'        => 'textarea',
                'label'       => 'Kommentarstext:',
                'value'       => $comment->content,
                'required'    => true,
                'validation'  => ['not_empty'],
            ],
            'id' => [
                'type'        => 'hidden',
                'label'       => 'id',
                'value'          => $comment->id,
            ],
            'page' => [
                'type'        => 'hidden',
                'label'       => 'page',
                'value'       => $comment->page,
            ],
            'spara' => [
                'type'      => 'submit',
                'callback'  => [$this, 'saveEdit'],
            ],
            /*
            'submit-fail' => [
                'type'      => 'submit',
                'callback'  => [$this, 'callbackSubmitFail'],
            ],
            */
        ]);


        // Check the status of the form
        $form->check([$this, 'callbackSuccess'], [$this, 'callbackFail']);

        $this->di->theme->setTitle("Redigera kommentar");
        
        $this->di->views->addString("$output", 'main');
        
        $this->di->views->add('default/page', [
            'title' => "Redigera kommentar",
            'content' => $form->getHTML()
        ]);
    }


    /**
     * Callback for submit-button.
     *
     */
    public function saveEdit($form)
    {
        /*
        $form->AddOutput("<p><i>DoSubmit(): Form was submitted. Do stuff (save to database) and return true (success) or false (failed processing form)</i></p>");
        $form->AddOutput("<p><b>id: " . $form->Value('id') . "</b></p>");
        $form->AddOutput("<p><b>Namn: " . $form->Value('name') . "</b></p>");
        $form->AddOutput("<p><b>E-post: " . $form->Value('mail') . "</b></p>");
        $form->AddOutput("<p><b>Web: " . $form->Value('web') . "</b></p>");
        $form->AddOutput("<p><b>Kommentar: " . $form->Value('content') . "</b></p>");
        */
        
        $res = $this->comment->save([
            'id' => $form->Value('id'),
            'name' => $form->Value('name'),
            'mail' => $form->Value('mail'),
            'web' => $form->Value('web'),
            'content' => $form->Value('content'),
            'page' => $form->Value('page'),
            'timestamp' => time(),
        ]);

        //$form->saveInSession = true;
        return $res;
    }



    /**
     * Callback for submit-button.
     *
     */
    public function callbackSubmitFail($form)
    {
        $form->AddOutput("<p><i>DoSubmitFail(): Form was submitted but it failed to process/save/validate</i></p>");
        return false;
    }



    /**
     * Callback What to do if the form was submitted?
     *
     */
    public function callbackSuccess($form)
    {
        //$form->AddOUtput("<p><i>Form was submitted and the callback method returned true.</i></p>");
        $this->redirectTo("comment/index/{$form->Value('page')}");
    }



    /**
     * Callback What to do when form could not be processed?
     *
     */
    public function callbackFail($form)
    {
        $form->AddOutput("<p><i>Något gick fel.</i></p>");
        $form->saveInSession = true;
        $this->redirectTo();
    }
       


        /**
     * Remove comment.
     *
     * @return void
     */
    public function removeAction($id)
    {
        // find page's page and use for redirect
        $commentToDelete = $this->comment->find($id);
        $page = $commentToDelete->page;
        
        $this->comment->delete($id);

        $this->redirectTo("comment/index/$page");
    }
    
    
    /**
     * Remove all comments from a page.
     *
     * @return void
     */
    public function removePageAction($page)
    {
        $this->comment->removePage($page);

        $this->redirectTo("comment/index/$page");
    }
}