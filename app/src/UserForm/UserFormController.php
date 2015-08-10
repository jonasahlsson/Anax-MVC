<?php

namespace Joah\UserForm;

/**
 * Anax base class for wrapping sessions.
 *
 */
class UserFormController
{
    use \Anax\DI\TInjectionaware;

    /**
     * Index action using external form.
     *
     */
    public function indexAction($user = null)
    {
            $this->editAction($user);
    }

    /**
     * Edit action using external form.
     *
     */
    public function editAction($user = null)
    {
        // dump($_SESSION);
        
        $this->di->session();
        
        $user = isset($user) ? $user : null;
        
        $task = isset($user) ? "Redigera" : "Skapa ny";
        
        $form = new \Joah\UserForm\CUserForm($user);
        $form->setDI($this->di);
        $form->check();

        $this->di->theme->setTitle("$task användare");
        $this->di->views->add('users/page', [
            'title' => "$task användare",
            'content' => $form->getHTML()
        ]);
    }

    /**
     * Login
     *
     */
    public function LoginAction()
    {
        $this->di->session();
        
        $form = new \Joah\UserForm\CLoginForm();
        $form->setDI($this->di);
        
        $form->check();

        $this->di->theme->setTitle("Inloggning");
        $this->di->views->add('users/page', [
            'title' => "Inloggning",
            'content' => $form->getHTML()
        ]);
    }
    
    /**
     * Logout
     *
     */
    public function LogoutAction()
    {
        $this->di->session();
        
        $form = new \Joah\UserForm\CLogoutForm();
        $form->setDI($this->di);
        
        $form->check();

        $content = $form->getHTML();
        
        
        $this->di->theme->setTitle("Utloggning");
        $this->di->views->add('users/page', [
            'title' => "Logga ut",
            'content' => $content
        ]);
    }    
}
