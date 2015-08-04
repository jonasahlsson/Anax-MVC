<?php

namespace Joah\Forum;

class ForumController implements \Anax\DI\IInjectionAware
{
    use \Anax\DI\TInjectable,
        \Anax\MVC\TRedirectHelpers;
    
    protected $question;
    protected $answer;
    protected $comment;
    
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

        
        // activate session
        $this->di->session(); // Will load the session service which also starts the session

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

    }
    
    
    public function testAction($id) 
    {
        // test route
        echo "hej!<br>";
        
        // fetch tag objects and turn into string
        $tagString =$this->tag->tagsToString($id);
        
        echo $tagString;
        
        $this->tag->tagStringToArray($tagString);
        
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
            ['title', 'content', 'user_id',  'timestamp']
        );
     
        $this->db->execute([
            'testfråga1',
            'Det här är frågetexten till testfråga1',
            1,
            $now
        ]);

        
        // test data answer
        $this->db->insert(
            'answer',
            ['question_id', 'content', 'user_id',  'timestamp']
        );
        
        $this->db->execute([
            1,
            'Det här är en svarstext1 kopplad till testfråga1',
            2,
            $now
        ]);
        
        $this->db->execute([
            1,
            'Det här är en svarstext2 kopplad till testfråga1',
            1,
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
            'Det här är en kommentarstext1 kopplad till testfråga1',
            2,
            $now
        ]);
        
        // test data answer comment 
        $this->db->execute([
            1,
            1,
            'Det här är en kommentarstext1 kopplad till svar1 på fråga1',
            1,
            $now
        ]);
        
        $this->db->execute([
            1,
            1,
            'Det här är en kommentarstext2 kopplad till svar1 på fråga1',
            1,
            $now
        ]);

        $this->db->execute([
            1,
            2,
            'Det här är en kommentarstext1 kopplad till svar2 på fråga1',
            2,
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
            'husdjur'
        ]);

        // test data tags2question 
        $this->db->insert(
            'tag2question',
            ['tag_id', 'question_id']
        );

        $this->db->execute([
            1,
            1
        ]);

        $this->db->execute([
            4,
            1
        ]);
        
    }
    
    /**
     *  view a question with answers and comments.
     */
    public function viewAction($id)
    {
        
        $this->theme->setTitle("Fråga");
        // $this->views->addString('Här kollar vi på en fråga');
        
        // a question, as an object
        $question = $this->question->find($id);

        // comments belonging to question as array of object
        $questionComments = $this->comment->findQuestionComments($id);
        
        // multiple answers as array of objects
        $answers = $this->answer->findAnswers($id);
        
        // comments belonging to answers as array of object
        $answerComments = $this->comment->findAnswerComments($id);
        
        
        $this->views->add('forum/view-question', [
            'question' => $question,
            'questionComments' => $questionComments,
            'answers' => $answers,
            'answerComments' => $answerComments
        ]);
        
        $this->views->addString('Lite text i en sidebar. Eventuellt lägga en knapp för att skapa en egen fråga här?', 'sidebar');

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
     *  Print an question overview
     *  
     *  @return void
     */
    public function overviewQuestionAction()
    {
    $all = $this->question->findAll();
    
        $this->theme->setTitle('Översikt frågor');
        $this->views->add('forum/overview-question', [
            'title' => "Översikt frågor",
            'questions' => $all,
            
        ]);
    
    }
    
    
    /**
     * Create a new question
     *
     * @return void
     */
    public function newQuestionAction()
    {
        $this->theme->setTitle('Ställ en fråga!');
        // test
        $this->session->set('user_id', 1);
        
        
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
        // save question
        $res = $this->question->save([
        'id' => null ==! $form->Value('id') ? $form->Value('id') : null,
        'title' => $form->Value('title'),
        'content' => $form->Value('content'),
        'user_id' => $this->session->get('user_id'),
        'timestamp' => date('Y-m-d H:i:s'),
        ]);
    
        // fetch question_id
        $question_id = null ==! $form->Value('id') ? $form->Value('id') : $this->db->lastInsertId();
    
        $this->saveTags($form, $question_id);
        
        // lägg till koll av saveTags. sätta And emellan? eller multiplicera? finns det en 0 så blir det inte 1.
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

        $this->theme->setTitle('Redigera fråga');
        // test
        $this->session->set('user_id', 1);

        // fetch question
        $q = $this->question->find($id);

        // check if question was found
        if (empty($q)) {
            die("question_id = '$id' not found");
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
    
}