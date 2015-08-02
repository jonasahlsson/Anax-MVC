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
        $this->di->session();
        
        $user = isset($user) ? $user : null;
        
        $form = new \Joah\UserForm\CUserForm($user);
        $form->setDI($this->di);
        $form->check();

        $this->di->theme->setTitle("Formulär användaruppgifter");
        $this->di->views->add('users/page', [
            'title' => "Formulär användaruppgifter",
            'content' => $form->getHTML()
        ]);
    }
}
