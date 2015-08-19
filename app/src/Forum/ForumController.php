<?php

namespace Joah\Forum;

class ForumController implements \Anax\DI\IInjectionAware
{
    use \Anax\DI\TInjectable,
        \Anax\MVC\TRedirectHelpers;
    
    protected $question;
    protected $answer;
    protected $comment;
    protected $vote;
    
    /**
     * Initialize the controller.
     *
     * @return void
     */
    public function initialize()
    {
        // load question model
        $this->question = new \Joah\Forum\Question();
        $this->question->setDI($this->di);

        // load answer model
        $this->answer = new \Joah\Forum\Answer();
        $this->answer->setDI($this->di);

        // load comment model
        $this->comment = new \Joah\Forum\Comment();
        $this->comment->setDI($this->di);

        // load tag model
        $this->tag = new \Joah\Forum\Tag();
        $this->tag->setDI($this->di);
        
        // load vote model
        $this->vote = new \Joah\Forum\Vote();
        $this->vote->setDI($this->di);

        // // activate session
        // $this->di->session(); // Will load the session service which also starts the session

        // load CForm 
        $this->di->set('form', '\Mos\HTMLForm\CForm');
    }

    //set up database connection and add some example posts
    public function setupAction() {

        $this->views->addString('Databastabeller skapade');
        
        $this->theme->setTitle('Databastabeller skapade');
        // set to verbose to see what is happening
        // $this->db->setVerbose(); 

        // remove and create new question table
        $this->db->dropTableIfExists('question')->execute();
     
        $this->db->createTable(
            'question',
            [
                'id' => ['integer', 'primary key', 'not null', 'auto_increment'],
                'title' => ['varchar(80)'],
                'content' => ['text'],
                'user_id' => ['integer'],
                'timestamp' => ['datetime'],
                'accepted_answer' => ['integer'],
            ]
        )->execute();

        // remove and create new answer table
        $this->db->dropTableIfExists('answer')->execute();
     
        $this->db->createTable(
            'answer',
            [
                'id' => ['integer', 'primary key', 'not null', 'auto_increment'],
                'question_id' => ['integer'],
                'content' => ['text'],
                'user_id' => ['integer'],
                'timestamp' => ['datetime'],
            ]
        )->execute();

        // remove and create new comment table
        $this->db->dropTableIfExists('comment')->execute();
     
        $this->db->createTable(
            'comment',
            [
                'id' => ['integer', 'primary key', 'not null', 'auto_increment'],
                'question_id' => ['integer'],
                'answer_id' => ['integer'],
                'content' => ['text'],
                'user_id' => ['integer'],
                'timestamp' => ['datetime'],
            ]
        )->execute();
        
        // remove and create new tag table
        $this->db->dropTableIfExists('tag')->execute();
     
        $this->db->createTable(
            'tag',
            [
                'id' => ['integer', 'primary key', 'not null', 'auto_increment'],
                'tag_text' => ['varchar(80)'],
            ]
        )->execute();
        
        // remove and create new tag2question table
        $this->db->dropTableIfExists('tag2question')->execute();
     
        $this->db->createTable(
            'tag2question',
            [
                'id' => ['integer', 'primary key', 'not null', 'auto_increment'],
                'tag_id' => ['integer'],
                'question_id' => ['integer'],
            ]
        )->execute();
        
        
        // remove and create new vote table
        $this->db->dropTableIfExists('vote')->execute();
     
        $this->db->createTable(
            'vote',
            [
                'id' => ['integer', 'primary key', 'not null', 'auto_increment'],
                'vote_type' => ['integer'],
                'vote_on' => ['integer'],
                'user_id' => ['integer'],
                'points' => ['integer'],
            ]
        )->execute();
        

    }
    
    
    public function testAction($vote_type, $vote_on, $user_id, $points) 
    {
        // test route
        $this->vote->vote(['vote_type' => $vote_type, 'vote_on' => $vote_on, 'user_id' => $user_id, 'points' => $points]); 
    
    }
    
    /**
     *  Add some test data
     *  
     *  @return void
     */
    
    public function addTestDataAction() {
        
        //$now = time();
        $now = date('Y-m-d H:i:s');
        
        $this->views->addString('Testdata inläst i databasen');
        
        $this->theme->setTitle('Testdata');
        // set to verbose to see what is happening
         // $this->db->setVerbose(); 
        
        // test data question
        $this->db->insert(
            'question',
            ['title', 'content', 'user_id',  'timestamp', 'accepted_answer']
        );
     
        $this->db->execute([
            'Hur jagar ni sniglar?',
            'Jag har stora problem med **sniglar** som äter upp mina **smultron**.  Jag blir jättearg när jag tänker på det. Är det någon som har bra tips på hur man jagar sniglar!?',
            1,
            $now,
            2
        ]);

        $this->db->execute([
            'Hur får man ur det sista ur burken?',
            '###Jag gillar läsk### 
När jag dricker *läsk på burk* så får jag ofta problem med att jag inte kan få ur det sista ur burken. Jag brukar kasta fram och tillbaka med huvudet. Headbangarn lite som en hårdrockar, för att få ut det sista. Men är det en varm dag så hinner läsken torka fast innan jag har fått ut sista droppen! Vad ska jag göra för att få ut det sista ur burken?',
            2,
            $now
        ]);
        
        $this->db->execute([
            'Jag vill inte gå ut',
            'Jag gillar att vara inomhus. Jag trivs inte utomhus. Måste jag verkligen gå ut bara för att det *är fint väder*?',
            2,
            $now
        ]);
        
        
        // test data answer
        $this->db->insert(
            'answer',
            ['question_id', 'content', 'user_id',  'timestamp']
        );
        
        $this->db->execute([
            1,
            'Jag plockar dem i en hink. Jag ser det som bra vardagsmotion och jag brukar göra utfallssteg när jag plockar dem. Sen tömmer jag hinken i min chefs trädgård. Jag är missnöjd med min lön.',
            2,
            $now
        ]);
        
        $this->db->execute([
            1,
            'Jag klipper sniglarna med en **sax**.',
            3,
            $now
        ]);

        // test data comment 
        $this->db->insert(
            'comment',
            ['question_id', 'answer_id', 'content', 'user_id',  'timestamp']
        );
        
        // test data question comment 
        $this->db->execute([
            1,
            null,
            'Jag har samma problem! Vad har du provat hittills? Själv har jag provat att flytta upp mina tomatplantor på balkongen, 
            med de slemmiga sniglarna klättrar upp för väggarna. Fasaden på huset har blivit funktskadad av allt snigelslem.',
            2,
            $now
        ]);

        // test data question comment 
        $this->db->execute([
            1,
            null,
            'Jag har också det här problemet!',
            3,
            $now
        ]);
        // test data answer comment 
        $this->db->execute([
            1,
            1,
            'Använder du **handskar**? De är ju ganska slemmiga!',
            1,
            $now
        ]);
        
        $this->db->execute([
            1,
            1,
            'Ja! Hanskar är ett *måste*. Jag använder alltid dubbla par.',
            2,
            $now
        ]);

        $this->db->execute([
            1,
            2,
            'I hur många bitar klipper du dem?',
            1,
            $now
        ]);

        // test data tags 
        $this->db->insert(
            'tag',
            ['tag_text']
        );

        $this->db->execute([
            'inomhus'
        ]);

        $this->db->execute([
            'utomhus'
        ]);
        
        $this->db->execute([
            'sniglar'
        ]);
        
        $this->db->execute([
            'smultron'
        ]);
        
        $this->db->execute([
            'läsk'
        ]);
        
        // test data tags2question 
        $this->db->insert(
            'tag2question',
            ['tag_id', 'question_id']
        );

        $this->db->execute([
            2,
            1
        ]);

        $this->db->execute([
            4,
            1
        ]);
        
        $this->db->execute([
            3,
            1
        ]);
        
        $this->db->execute([
            5,
            2
        ]);
        
        $this->db->execute([
            1,
            3
        ]);
        
        $this->db->execute([
            2,
            3
        ]);
    
        
        // test data vote 
        $this->db->insert(
            'vote',
            ['vote_type', 'vote_on', 'user_id', 'points']
        );
        $this->db->execute([
            1,
            1,
            3,
            1
        ]);
        
        $this->db->execute([
            1,
            3,
            1,
            -1
        ]);
        
        $this->db->execute([
            1,
            3,
            3,
            -1
        ]);        

        $this->db->execute([
            1,
            2,
            1,
            1
        ]);
        
        $this->db->execute([
            1,
            2,
            3,
            -1
        ]);
    }
    
    /**
     *  view a question with answers and comments.
     */
    public function viewAction($id)
    {
        
        // check id is valid
        if(!is_numeric($id)) {
            die("ID=$id not recognized");
        }
        
        $this->theme->setTitle("Fråga");
        // $this->views->addString('Här kollar vi på en fråga');
        
        // a question, as an object
        $question = $this->question->find($id);
        
        if(empty($question)){
            die("question ID=$id was not found");
        }   
        
        // fetch tags
        $tags = $this->tag->fetchTags($id);
        
        // comments belonging to question as array of object
        $questionComments = $this->comment->findQuestionComments($id);
        
        // multiple answers as array of objects
        $answers = $this->answer->findAnswers($id);
        
        // comments belonging to answers as array of object
        $answerComments = $this->comment->findAnswerComments($id);
        
        // run content through markdown and HTMLPurifier filters
        // question
        $question->content = $this->textFilter->doFilter($question->content, 'markdown');
        $question->content = $this->HTMLPurifier->purify($question->content);
        
        // questionComments
        foreach($questionComments as $comment) {
            $comment->content = $this->textFilter->doFilter($comment->content, 'markdown');
            $comment->content = $this->HTMLPurifier->purify($comment->content);
        }
        
        // answers
        foreach($answers as $answer) {
            $answer->content = $this->textFilter->doFilter($answer->content, 'markdown');
            $answer->content = $this->HTMLPurifier->purify($answer->content);
        }
        
        // answerComments
        foreach($answerComments as $comment) {
            $comment->content = $this->textFilter->doFilter($comment->content, 'markdown');
            $comment->content = $this->HTMLPurifier->purify($comment->content);
        }
        
        
        $vote = $this->vote;
        
        $this->views->add('forum/view-question', [
            'question' => $question,
            'tags' => $tags,
            'questionComments' => $questionComments,
            'answers' => $answers,
            'answerComments' => $answerComments,
            'vote' => $vote
        ]);
        
        // $this->views->addString('Lite text i en sidebar. Eventuellt lägga en knapp för att skapa en egen fråga här?', 'sidebar');

    }
    
    
    /**
     *  Default action
     */
    public function indexAction()
    {        
        // show overview as default
        $this->overviewQuestionAction();
    }

    
    /**
     *  Display overview of questions
     *  
     *  @return void
     */
    public function overviewQuestionAction()
    {
        // fetch questions
        $all = $this->question->findAll();
        
        // fetch tags, run markdown filter
        foreach($all as $question) {
            
            // fetch array of objects with tag info
            $question->tags = $this->tag->findTagByQuestion($question->id);
            
            // markdown and HTMLPurifier filter
            $question->content = $this->textFilter->doFilter($question->content, 'markdown');
            $question->content = $this->HTMLPurifier->purify($question->content);
        }
    
    
        $this->theme->setTitle('Översikt frågor');
        $this->views->add('forum/overview-question', [
            'title' => "Översikt frågor",
            'questions' => $all,
        ]);
        
        $this->views->addString("<a href='" . $this->url->create('forum/new-question') . "'>STÄLL DIN FRÅGA!</a>");
        
        
    }
    
    
    /**
     * Create a new question
     *
     * @return void
     */
    public function newQuestionAction()
    {
        // check if logged in.
        if(!$this->users->isLoggedIn()) {
        // redirect to login page and return after successfull login
            $this->redirectTo($this->url->create("users/login") . "?url=" . $this->request->getRoute());
            die("Funktionen kräver inloggning");
        }
    
        $this->theme->setTitle('Ställ en fråga!');

        
        $form = $this->di->form->create([], [
            'title' => [
                'type'        => 'text',
                'label'       => 'Titel:',
                'required'    => true,
                'validation'  => ['not_empty'],
            ],
            'content' => [
                'type'        => 'textarea',
                'label'       => 'Frågetext:',
                'required'    => true,
                'validation'  => ['not_empty'],
            ],
            'tags' => [
                'type'        => 'text',
                'label'       => 'Taggar (ex: #inomhus #sniglar)',
                'validation'  => array('custom_test' => array('message' => 'Please only use #, letters, numbers and -.', 'test' => 'return empty($value) OR ctype_alnum(str_replace(array(" ","-","_","#","å","ä","ö"), "", $value));')),
            ],

            'spara' => [
                'type'      => 'submit',
                'callback'  => [$this, 'saveQuestion'],
            ],

        ]);
        
        // Check the status of the form
        $status = $form->check();
     
        // if form was submitted
        if ($status === true) {
            //$form->AddOUtput("<p><i>Sparat!</i></p>");
            $this->redirectTo("forum/view/{$this->question->id}");
        } 
        // If form could not be submitted.
        else if ($status === false) {
            // What to do when form could not be processed?
            $form->AddOutput("<p><i>Något gick fel.</i></p>");
            
            $form->saveInSession = true;
            
            $this->redirectTo();
        }
        
        // Print form
        $this->di->views->add('forum/page', [
        'title' => "Ställ din fråga!",
        'content' => $form->getHTML()
        ]);

    }
    
    
    /**
     *  callback used to Save question 
     */
    public function saveQuestion($form)
    {
        
        // fetch user id from session
        $user_session = $this->session->get('user');
        $user_id = $user_session['id'];
        
        // save question
        $res = $this->question->save([
        'id' => null ==! $form->Value('id') ? $form->Value('id') : null,
        'title' => $form->Value('title'),
        'content' => $form->Value('content'),
        'user_id' => $user_id,
        'timestamp' => date('Y-m-d H:i:s'),
        ]);
    
        // fetch question_id
        $question_id = null ==! $form->Value('id') ? $form->Value('id') : $this->db->lastInsertId();
    
        $this->saveTags($form, $question_id);
        
        // Add a check for saveTAgs. AND? multiplication 1*0 = 0?
        return $res;
    }

    
    /**
     *  callback used to save tag
     */
    public function saveTags($form, $question_id)
    {
        // save tags
        // should I put this code elsewhere?

        // clear old entries in tag2question
        $this->db->delete('tag2question', "question_id = $question_id");
        $this->db->execute();
        
        //get string of tag(s) from form
        $tagString = $form->value('tags');

        // turn string into array
        $tagArray = $this->tag->tagStringToArray($tagString);
        
        // check if tag already exists
        $sql = "SELECT * FROM tag WHERE tag_text LIKE ?";
            
        // loop through array of tags
        foreach ($tagArray as $tag) {
            
            // check if tag already exists
            $existingTags = $this->db->executeFetchAll($sql, [$tag]);
            if(!empty($existingTags[0])) {
                // tags already in table, store id
                $tagID = $existingTags[0]->id;
            }
            else {
                // if not already there
                // add tag to tag table
                $this->db->insert('tag', ['tag_text'] , [$tag]);
                $this->db->execute();
                // store id of tag
                $tagID = $this->db->lastInsertId();
            }
   
            // add tag_id and question_id to tag2question
            $this->db->insert('tag2question', ['tag_id' => $tagID, 'question_id' => $question_id]);
            $this->db->execute();
        }        
    }
    
    
    /**
     * Edit question
     *
     * @return void
     */
    public function editQuestionAction($id = null)
    {

        // check if logged in.
        if(!$this->users->isLoggedIn()) {
        // redirect to login page and return after successfull login
            $this->redirectTo($this->url->create("users/login") . "?url=" . $this->request->getRoute());
            
        }
        
    
        $this->theme->setTitle('Redigera fråga');
        // test
        // $this->session->set('user_id', 1);

        // fetch question
        $q = $this->question->find($id);

        // check if question was found
        if (empty($q)) {
            die("question_id = '$id' not found");
        }
        
        // check editing rights
        if(!($this->users->verifyLogin($this->question->user_id))) {
            die("Du saknar rättigheter för den här åtgärden.");
        }
        
        
        $form = $this->di->form->create([], [
            'id' => [
                'type'        => 'hidden',
                'value'       => $id,
                'label'       => 'id',
            ],

            'title' => [
                'type'        => 'text',
                'value'       => $this->question->title,
                'label'       => 'Titel:',
                'required'    => true,
                'validation'  => ['not_empty'],
            ],
            
            'content' => [
                'type'        => 'textarea',
                'value'       => $this->question->content,
                'label'       => 'Frågetext:',
                'required'    => true,
                'validation'  => ['not_empty'],
            ],
            
            'tags' => [
                'type'        => 'text',
                'value'       => $this->tag->tagsToString($id),
                'label'       => 'Taggar (ex: #inomhus #sniglar)',
                'validation'  => array('custom_test' => array('message' => 'Please only use #, letters, numbers and -.', 'test' => 'return empty($value) OR ctype_alnum(str_replace(array(" ","-","_","#","å","ä","ö"), "", $value));')),
            ],
            
            'spara' => [
                'type'      => 'submit',
                'callback'  => [$this, 'saveQuestion'],
            ],

        ]);
        
        // Check the status of the form
        $status = $form->check();
     
        // if form was submitted
        if ($status === true) {
            //$form->AddOUtput("<p><i>Sparat!</i></p>");
            $this->redirectTo("forum/view/{$this->question->id}");
        } 
        // If form could not be submitted.
        else if ($status === false) {
            // What to do when form could not be processed?
            $form->AddOutput("<p><i>Något gick fel.</i></p>");
            
            $form->saveInSession = true;
            
            $this->redirectTo();
        }
        
        // Print form
        $this->di->views->add('forum/page', [
        'title' => "Redigera din fråga",
        'content' => $form->getHTML()
        ]);

    }
    

    /**
     * Delete question
     *
     * @return void
     */
    public function deleteQuestionAction($id)
    {
        // delete question
        $this->question->delete($id);
        // todo. delete comments and answers
        
        $this->redirectTo("forum/overview-question");
    }

    
    /**
     *  Display overview of tags
     *  
     *  @return void
     */
    public function overviewTagAction()
    {
        $this->theme->setTitle('Översikt taggar');

        // fetch counted tags
        $tags = $this->tag->countTags();
            
        $this->theme->setTitle('Översikt taggar');
        $this->views->add('forum/overview-tag', [
            'title' => "Översikt taggar",
            'tags' => $tags,
            
        ]);
    
    }

    /**
     *  view question associated with an tag
     */
    public function viewTagAction($tag_id)
    {
        $this->theme->setTitle('Tagg');
        $questions = $this->tag->findQuestionByTag($tag_id);

        // fetch tags for each question and run markdown filter
        foreach($questions as $question) {
        
            // fetch array of objects with tag info
            $question->tags = $this->tag->findTagByQuestion($question->id);
        
            $question->content = $this->textFilter->doFilter($question->content, 'markdown');
            $question->content = $this->HTMLPurifier->purify($question->content);
        };
        
        
        // $this->views->add('forum/view-question-by-tag', [
        $this->views->add('forum/overview-question', [
            'title' => "Frågor med tagg #{$this->tag->find($tag_id)->tag_text}",
            'questions' => $questions
        ]);
        
        // $this->views->addString('Lite text i en sidebar. Här kanske jag kan lägga in en lista på populära taggar?', 'sidebar');

    }

    /**
     *  Fetch user contributions. Questions, Answers, and comments
     */
    public function fetchUserContribution($user_id)
    {
        // creates objects
        $this->initialize();
        
        $questions = $this->question->findQuestionByUser($user_id);
        $answers = $this->answer->findAnswerByUser($user_id);
        $comments = $this->comment->findCommentByUser($user_id);
        
        // makdown and HTMLPurifier filter
        foreach($answers as $answer) {
            $answer->content = $this->textFilter->doFilter($answer->content, 'markdown');
            $answer->content = $this->HTMLPurifier->purify($answer->content);
        }
        foreach($comments as $comment) {
            $comment->content = $this->textFilter->doFilter($comment->content, 'markdown');
            $comment->content = $this->HTMLPurifier->purify($comment->content);
        }
        
        
        return ['questions' => $questions, 'answers' => $answers, 'comments' => $comments];
        
    }
    
    
    /**
     * Create an answer
     *
     * @return void
     */
    public function answerAction($question_id = null)
    {
        
        // check if logged in.
        if(!$this->users->isLoggedIn()) {
            // redirect to login page and return after successfull login
            $this->redirectTo($this->url->create("users/login") . "?url=" . $this->request->getRoute());
            die("Funktionen kräver inloggning");
        }
        
        $this->theme->setTitle('Besvara en fråga');

        // fetch question
        $q = $this->question->find($question_id);

        // check if question was found
        if (empty($q)) {
            die("question_id ='$question_id' not found");
        }
        
        
        $form = $this->di->form->create([], [
            'question_id' => [
                'type' => 'hidden',
                'value'       => $question_id,
                'label'       => 'question_id',
            ],
            'content' => [    
                'type'        => 'textarea',
                'label'       => 'Svarstext:',
                'required'    => true,
                'validation'  => ['not_empty'],
            ],
            'spara' => [
                'type'      => 'submit',
                'callback'  => [$this, 'saveAnswer'],
            ],

        ]);
        
        // Check the status of the form
        $status = $form->check();
     
        // if form was submitted
        if ($status === true) {
            //$form->AddOUtput("<p><i>Sparat!</i></p>");
            $this->redirectTo("forum/view/{$this->question->id}");
        } 
        // If form could not be submitted.
        else if ($status === false) {
            // What to do when form could not be processed?
            $form->AddOutput("<p><i>Något gick fel.</i></p>");
            
            $form->saveInSession = true;
            
            $this->redirectTo();
        }
        
        // Print form
        $this->di->views->add('forum/page', [
        'title' => "Besvara en fråga",
        'content' => $form->getHTML()
        ]);

    }
    
    
    /**
     *  callback used to Save answer 
     */
    public function saveAnswer($form)
    {
        // fetch user id from session
        $user_session = $this->session->get('user');
        $user_id = $user_session['id'];
        
        // save question
        $res = $this->answer->save([
        'id' => null ==! $form->Value('id') ? $form->Value('id') : null,
        'question_id' => $form->Value('question_id'),
        'content' => $form->Value('content'),
        'user_id' => $user_id,
        'timestamp' => date('Y-m-d H:i:s'),
        ]);
    
        return $res;
    }
    
    
    /**
     * Edit answer
     *
     * @return void
     */
    public function editAnswerAction($answer_id = null)
    {

        $this->theme->setTitle('Redigera svar');

        // fetch question
        $a = $this->answer->find($answer_id);

        // check if answer was found
        if (empty($a)) {
            die("answer_id = '$answer_id' not found");
        }

        // check editing rights
        if(!($this->users->verifyLogin($this->answer->user_id))) {
            die("Du saknar rättigheter för den här åtgärden.");
        }      
        
        
        $form = $this->di->form->create([], [
            'id' => [
                'type'        => 'hidden',
                'value'       => $answer_id,
                'label'       => 'id',
            ],
            'question_id' => [
                'type' => 'hidden',
                'value'       => $this->answer->question_id,
                'label'       => 'question_id',
            ],
            'content' => [
                'type'        => 'textarea',
                'value'       => $this->answer->content,
                'label'       => 'Svarstext:',
                'required'    => true,
                'validation'  => ['not_empty'],
            ],
            'spara' => [
                'type'      => 'submit',
                'callback'  => [$this, 'saveAnswer'],
            ],

        ]);
        
        // Check the status of the form
        $status = $form->check();
     
        // if form was submitted
        if ($status === true) {
            //$form->AddOUtput("<p><i>Sparat!</i></p>");
            $this->redirectTo("forum/view/{$this->answer->question_id}/#answer-{$this->answer->id}");
        } 
        // If form could not be submitted.
        else if ($status === false) {
            // What to do when form could not be processed?
            $form->AddOutput("<p><i>Något gick fel.</i></p>");
            
            $form->saveInSession = true;
            
            $this->redirectTo();
        }
        
        // Print form
        $this->di->views->add('forum/page', [
        'title' => "Redigera ditt svar",
        'content' => $form->getHTML()
        ]);

    }

    
    /**
     * Create a comment
     *
     * @return void
     */
    public function commentAction($question_id = null, $answer_id = null)
    {
        // check if logged in.
        if(!$this->users->isLoggedIn()) {
            // redirect to login page and return after successfull login
            $this->redirectTo($this->url->create("users/login") . "?url=" . $this->request->getRoute());
            die("Funktionen kräver inloggning");
        }
    
        $this->theme->setTitle('Kommentera');

        // fetch question
        $q = $this->question->find($question_id);
        // fetch answer
        $a = $this->answer->find($answer_id);

        // check if question was found
        if (empty($q)) {
            die("question_id ='$question_id' not found");
        }
        // check if answer_id matches question_id
        if (!empty($a) AND $question_id !== $a->question_id) {
            die("question_id and answer_id doesn't match");
        }
        
        // the form
        $form = $this->di->form->create([], [
            'question_id' => [
                'type' => 'hidden',
                'value'       => $question_id,
                'label'       => 'question_id',
            ],
            'answer_id' => [
                'type' => 'hidden',
                'value'       => $answer_id,
                'label'       => 'answer_id',
            ],
            'content' => [    
                'type'        => 'textarea',
                'label'       => 'Kommentarstext:',
                'required'    => true,
                'validation'  => ['not_empty'],
            ],
            'spara' => [
                'type'      => 'submit',
                'callback'  => [$this, 'saveComment'],
            ],

        ]);
        
        // Check the status of the form
        $status = $form->check();
     
        // if form was submitted
        if ($status === true) {
            //$form->AddOUtput("<p><i>Sparat!</i></p>");
            $this->redirectTo("forum/view/{$this->question->id}");
        } 
        // If form could not be submitted.
        else if ($status === false) {
            // What to do when form could not be processed?
            $form->AddOutput("<p><i>Något gick fel.</i></p>");
            
            $form->saveInSession = true;
            
            $this->redirectTo();
        }
        
        // Print form
        $this->di->views->add('forum/page', [
        'title' => "Kommentera",
        'content' => $form->getHTML()
        ]);

    }
    
    
    /**
     *  callback used to Save comment 
     */
    public function saveComment($form)
    {
        // fetch user id from session
        $user_session = $this->session->get('user');
        $user_id = $user_session['id'];
        
        // keep numeric ids and turn empty ids into null
        $answer_id = is_numeric($form->Value('answer_id')) ? $form->Value('answer_id') : null;
        
        // save comment
        $res = $this->comment->save([
        'id' => null ==! $form->Value('id') ? $form->Value('id') : null,
        'question_id' => $form->Value('question_id'),
        'answer_id' => $answer_id,
        'content' => $form->Value('content'),
        'user_id' => $user_id,
        'timestamp' => date('Y-m-d H:i:s'),
        ]);
    
        return $res;
    }
    
    
    /**
     * Edit comment
     *
     * @return void
     */
    public function editCommentAction($comment_id = null)
    {
   
        $this->theme->setTitle('Redigera svar');

        // fetch comment
        $c = $this->comment->find($comment_id);

        // check if question was found
        if (empty($c)) {
            die("comment_id = '$comment_id' not found");
        }
        
        // check editing rights
        if(!($this->users->verifyLogin($this->comment->user_id))) {
            die("Du saknar rättigheter för den här åtgärden.");
        }
        
        
        $form = $this->di->form->create([], [
            'id' => [
                'type'        => 'hidden',
                'value'       => $comment_id,
                'label'       => 'id',
            ],
            'question_id' => [
                'type'        => 'hidden',
                'value'       => $this->comment->question_id,
                'label'       => 'question_id',
            ],
            'answer_id' => [
                'type'        => 'hidden',
                'value'       => $this->comment->answer_id,
                'label'       => 'answer_id',
            ],
            'content' => [
                'type'        => 'textarea',
                'value'       => $this->comment->content,
                'label'       => 'Kommentarstext:',
                'required'    => true,
                'validation'  => ['not_empty'],
            ],
            'spara' => [
                'type'      => 'submit',
                'callback'  => [$this, 'saveComment'],
            ],

        ]);
        
        // Check the status of the form
        $status = $form->check();
     
        // if form was submitted
        if ($status === true) {
            //$form->AddOUtput("<p><i>Sparat!</i></p>");
            $this->redirectTo("forum/view/{$this->comment->question_id}/#comment-{$this->comment->id}");
        } 
        // If form could not be submitted.
        else if ($status === false) {
            // What to do when form could not be processed?
            $form->AddOutput("<p><i>Något gick fel.</i></p>");
            
            $form->saveInSession = true;
            
            $this->redirectTo();
        }
        
        // Print form
        $this->di->views->add('forum/page', [
        'title' => "Redigera din kommentar",
        'content' => $form->getHTML()
        ]);

    }
    
    
    /**
     *  Display overview of latest questions
     *  
     *  @return void
     */
    public function latestQuestionsAction()
    {
        // fetch latest questions
        $all = $this->question->latestQuestions(5);

        // fetch tags for questions and apply markdown filter
        foreach($all as $question) {
            // fetch array of objects with tag info
            $question->tags = $this->tag->findTagByQuestion($question->id);
            
            // markdown and HTMLPurifier filter
            $question->content = $this->textFilter->doFilter($question->content, 'markdown');
            $question->content = $this->HTMLPurifier->purify($question->content);
        }
    
        // add view
        $this->views->add('forum/latest-questions', [
        // $this->views->add('forum/overview-question', [
            'title' => "Senaste frågorna",
            'questions' => $all,
        ]);
    
    }
    
    
    /**
     *  Display popular tags
     *  
     *  @return void
     */
    public function popularTagAction()
    {
        // fetch counted tags
        $tags = $this->tag->countTags();
            
        $this->views->add('forum/popular-tag', [
            'title' => "Populära taggar",
            'tags' => $tags,
        ]);
    }
        
    
    /**
     * List active users.
     *
     * @return void
     */
     public function activeUsersAction($num = 3)
    {
        $this->initialize();
     
        $all = $this->users->findAll();
        
        // get top three askers
        $askers = $this->question->activeUsers($num);
        $answerers = $this->answer->activeUsers($num);
        $commentators = $this->comment->activeUsers($num);

        // send to view
        $this->views->add('forum/active-users', [
            'askers' => $askers,
            'answerers' => $answerers,
            'commentators' => $commentators,
            'title' => "Aktiva användare",
        ]);
    }

    
    /**
     * Accept an answer
     *
     * @return void
     */
     public function acceptAnswerAction($question_id, $answer_id)
    {
        // check id is valid and that question and answer exists.
        if(!is_numeric($question_id) OR !is_numeric($answer_id)) {
            die("ID not recognized");
        }
        
        $question = $this->question->find($question_id);
        if(empty($question)){
            die("question ID=$question_id was not found");
        }        
        $answer = $this->answer->find($answer_id);
        if(empty($answer)){
            die("answer ID=$answer_id was not found");
        }                
        
        // check if logged in.
        if(!$this->users->isLoggedIn()) {
        // redirect to login page and return after successfull login
            $this->redirectTo($this->url->create("users/login") . "?url=" . $this->request->getRoute());
        }
        
        // check editing rights
        if(!($this->users->verifyLogin($this->question->user_id))) {
            die("Du saknar rättigheter för den här åtgärden.");
        }
        
        // set accepted answer property
        $this->question->accepted_answer = $answer_id;
        // save object
        $this->question->save();
        // redirect to question page
        $this->redirectTo($this->url->create("forum/view/$question_id"));
    }
 
    /*
     * Vote
     *
     * @return void
     */
 
    public function voteAction($vote_type, $vote_on, $user_id, $points) 
    {
        // call on vote method in vote model
        $this->vote->vote(['vote_type' => $vote_type, 'vote_on' => $vote_on, 'user_id' => $user_id, 'points' => $points]); 
        
        // redirect to url in querystring or to default forum
        $this->redirectTo($this->di->request->getGet('url', 'forum'));

    }
    
}