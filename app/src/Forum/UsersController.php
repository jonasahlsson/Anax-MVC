<?php

namespace Joah\Forum;
 
/**
 * A controller for users and admin related events.
 *
 */
class UsersController extends \Anax\Users\UsersController implements \Anax\DI\IInjectionAware
{

    
    /**
     * Initialize the controller.
     *
     * @return void
     */
    public function initialize()
    {
        // already in $di
        // $this->users = new \Anax\Users\User();
        // $this->users->setDI($this->di);
    }
    
    /**
     *  testaction
     */
    public function testAction($acronym, $password)
    {

    }
    
    
    // default route
    public function indexAction($acronym = null) {
        
        //show user list as default
        $this->listAction();
    }

    
    
    public function overviewAction($acronym = null) {
        $this->theme->setTitle("Basmodell och användare");
        
        // sort by acronym or default
        switch ($acronym) {
            case 'asc':
                $all = $this->users->findAllByAcroym('asc');
                break;
            case 'desc':
                $all = $this->users->findAllByAcroym('desc');
                break;
            default:
                $all = $this->users->findAll();
                break;
        }
        
        // normal find all
        //$all = $this->users->findAll();
        
        
        $this->views->add('users/overview', [
        'title' => "Basmodell och användare",
            'users' => $all,
            //'content' => "Länkar för demonstration av databasdriven basmodell och användarhantering",
            'content' => null,
            'links' => [
                [
                    'href' => $this->url->create('users/setup'),
                    'text' => "Initiera tabell user",
                ],
                [
                    'href' => $this->url->create('users/add'),
                    'text' => "Lägg till användare",
                ],
                [
                    'href' => $this->url->create('users/list'),
                    'text' => "Lista alla användare",
                ],
                [
                    'href' => $this->url->create('users/active'),
                    'text' => "Visa aktiva användare",
                ],
                [
                    'href' => $this->url->create('users/inactive'),
                    'text' => "Visa inaktiva användare",
                ],
                [
                    'href' => $this->url->create('users/deleted'),
                    'text' => "Visa användare i papperskorgen",
                ],
                                [
                    'href' => $this->url->create('users/index/asc'),
                    'text' => "Sortera användare efter akronym - stigande ordning",
                ],
                                [
                    'href' => $this->url->create('users/index/desc'),
                    'text' => "Sortera användare efter akronym - fallande ordning",
                ],
            ],
        ]);
        }
    
    
    public function setupAction() {
        
        $content = $this->fileContent->get('users/setup.md');
    
        $content = $this->textFilter->doFilter($content, 'shortcode, markdown');
        
        $this->views->add('users/content', [
        'content' => $content,
        ]);
        
        
        $this->theme->setTitle('Initiera tabell user');
        //$this->db->setVerbose(); 
     
        $this->db->dropTableIfExists('user')->execute();
     
        $this->db->createTable(
            'user',
            [
                'id' => ['integer', 'primary key', 'not null', 'auto_increment'],
                'acronym' => ['varchar(20)', 'unique', 'not null'],
                'email' => ['varchar(80)'],
                'name' => ['varchar(80)'],
                'password' => ['varchar(255)'],
                'created' => ['datetime'],
                'updated' => ['datetime'],
                'deleted' => ['datetime'],
                'active' => ['datetime'],
            ]
        )->execute();
        
        $this->db->insert(
            'user',
            ['acronym', 'email', 'name', 'password', 'created', 'active']
        );
     
        $now = gmdate('Y-m-d H:i:s');
     
        $this->db->execute([
            'admin',
            'admin@dbwebb.se',
            'Administrator',
            password_hash('admin', PASSWORD_DEFAULT),
            $now,
            $now
        ]);
     
        $this->db->execute([
            'doe',
            'doe@dbwebb.se',
            'John/Jane Doe',
            password_hash('doe', PASSWORD_DEFAULT),
            $now,
            $now
        ]);
    }
    
    
    /**
     * List all users.
     *
     * @return void
     */

     public function listAction()
    {
        $this->initialize();
     
        $all = $this->users->findAll();
     
        $this->theme->setTitle("Visa alla användare");
        $this->views->add('forum/overview-user', [
            'users' => $all,
            'title' => "Visa alla användare",
        ]);
    } 
 
    /**
     * List user with id.
     *
     * @param int $id of user to display
     *
     * @return void
     */
    public function idAction($id = null)
    {
        if(empty($id)) {
            die('Missing id');
        }
        
        $this->initialize();
     
        $user = $this->users->find($id);
        
        // fetch and extract questions, answers and comments to variables
        extract($this->di->ForumController->fetchUserContribution($id));
        
        $this->theme->setTitle("Visa användare");
        $this->views->add('forum/view-user', [
            'user' => $user,
            'title' => "Användare: " . $user->name,
            'questions' => $questions,
            'answers' => $answers,
            'comments' => $comments
        ]);
    }
    
    /**
     * Add new user.
     *
     *
     * @return void
     */
    public function addAction()
    {
        // display userform
        $controller = new \Joah\UserForm\UserFormController();
        $controller->setDI($this->di);
        $controller->indexAction();
        
    }

    /**
     * update user.
     *
     * @param int $id of user to update.
     *
     * @return void
     */
    public function editAction($id = null)
    {
        if (!isset($id)) {
            die("Missing id");
        }
        
        //fetch user if id was supplied
        $user = isset($id) ? $this->users->find($id) : null;
        
        // display userform
        $controller = new \Joah\UserForm\UserFormController();
        $controller->setDI($this->di);
        $controller->indexAction($user);
    }
    
    /**
     * Save user data.
     *
     * @param string $acronym of user to save.
     *
     * @return void
     */
    public function saveUser($acronym, $name, $password, $email, $created = null) 
    {
        $now = gmdate('Y-m-d H:i:s');
     
        $this->users->save([
            'acronym' => $acronym,
            'name' => $name,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'email' => $email,
            'created' => $created,
            'updated' => $now
        ]);
     
        
        //$url = $this->url->create('users/id/' . $this->users->id);
        $url = $this->url->create('users');
        $this->response->redirect($url);
        
    }
    
    
    /**
     * Delete user.
     *
     * @param integer $id of user to delete.
     *
     * @return void
     */
    public function deleteAction($id = null)
    {
        if (!isset($id)) {
            die("Missing id");
        }
     
        $res = $this->users->delete($id);
     
        $url = $this->url->create('users');
        $this->response->redirect($url);
    }
    
    /**
     * Delete (soft) user.
     *
     * @param integer $id of user to delete.
     *
     * @return void
     */
    public function softDeleteAction($id = null)
    {
        if (!isset($id)) {
            die("Missing id");
        }
     
        $now = gmdate('Y-m-d H:i:s');
     
        $user = $this->users->find($id);
     
        $user->deleted = $now;
        $user->save();
     
        $url = $this->url->create('users/id/' . $id);
        $this->response->redirect($url);
    }

        /**
     * List all deleted users.
     *
     * @return void
     */
    public function deletedAction()
    {
        $all = $this->users->query()
            ->Where('deleted is NOT NULL')
            ->execute();
     
        $this->theme->setTitle("Users that are deleted");
        $this->views->add('users/list-all', [
            'users' => $all,
            'title' => "Users that are deleted",
        ]);
    }
    
    /**
     * UnDelete (soft) user.
     *
     * @param integer $id of user to undelete.
     *
     * @return void
     */
    public function UnDeleteAction($id = null)
    {
        if (!isset($id)) {
            die("Missing id");
        }
     
        $now = gmdate('Y-m-d H:i:s');
     
        $user = $this->users->find($id);
     
        $user->deleted = null;
        $user->save();
     
        $url = $this->url->create('users/id/' . $id);
        $this->response->redirect($url);
    }
    
    /**
     * Activate user
     *
     * @param integer $id of user to activate.
     *
     * @return void
     */
    public function activateAction($id = null)
    {
        if (!isset($id)) {
            die("Missing id");
        }
     
        $now = gmdate('Y-m-d H:i:s');
     
        $user = $this->users->find($id);
     
        $user->active = $now;
        $user->deleted = null;
        $user->save();
     
        $url = $this->url->create('users/id/' . $id);
        $this->response->redirect($url);
    }
    
        /**
     * In-Activate user
     *
     * @param integer $id of user to in-activate.
     *
     * @return void
     */
    public function inActivateAction($id = null)
    {
        if (!isset($id)) {
            die("Missing id");
        }
     
        $now = gmdate('Y-m-d H:i:s');
     
        $user = $this->users->find($id);
     
        $user->active = null;
        $user->save();
     
        $url = $this->url->create('users/id/' . $id);
        $this->response->redirect($url);
    }
    
    /**
     * List all active and not deleted users.
     *
     * @return void
     */
    public function activeAction()
    {
        $all = $this->users->query()
            ->where('active IS NOT NULL')
            ->andWhere('deleted is NULL')
            ->execute();
     
        $this->theme->setTitle("Users that are active");
        $this->views->add('users/list-all', [
            'users' => $all,
            'title' => "Users that are active",
        ]);
    }
    
        /**
     * List all inactiveusers.
     *
     * @return void
     */
    public function inActiveAction()
    {
        $all = $this->users->query()
            ->where('active IS NULL')
            ->andWhere('deleted is NULL')
            ->execute();
     
        $this->theme->setTitle("Users that are inactive");
        $this->views->add('users/list-all', [
            'users' => $all,
            'title' => "Users that are inactive",
        ]);
    }
    
    /**
     * Login user.
     *
     * @return void
     */
    public function loginAction($url = null)
    {
        // display userform
        $controller = new \Joah\UserForm\UserFormController();
        $controller->setDI($this->di);
        $controller->loginAction($url);
        
    }

    /**
     * Logout user.
     *
     * @return void
     */
    public function logoutAction()
    {
        // display userform
        $controller = new \Joah\UserForm\UserFormController();
        $controller->setDI($this->di);
        $controller->logoutAction();
        
    }
  
}