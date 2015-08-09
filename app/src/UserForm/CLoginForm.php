<?php

namespace Joah\UserForm;

/**
 * A form for adding editing User data
 *
 */
class CLoginForm extends \Mos\HTMLForm\CForm
{
    use \Anax\DI\TInjectionaware,
        \Anax\MVC\TRedirectHelpers;
    
    // private $user;
    
    /**
     * Constructor
     *
     */
    public function __construct($user = null)
    {
        $acronym = isset($user->acronym) ? htmlentities($user->acronym) : null;
        parent::__construct([], [
            'acronym' => [
                'type'        => 'text',
                'label'       => 'Användarnamn:',
                'value'       => $acronym,
                'required'    => true,
                'validation'  => ['not_empty'],
            ],
            
            'password' => [
                'type'        => 'password',
                'label'       => 'Lösenord:',
                'required'    => true,
                'validation'  => ['not_empty'],
            ],
            'Login' => [
                'type'      => 'submit',
                'value'       => 'Logga in',
                'callback'  => [$this, 'callbackLogin'],
            ],
            // 'Logout' => [
                // 'type'      => 'submit',
                // 'value'       => 'Logga ut',
                // 'callback'  => [$this, 'callbackLogout'],
            // ],
            // 'submit-fail' => [
                // 'type'      => 'submit',
                // 'callback'  => [$this, 'callbackSubmitFail'],
            // ],
            
        ]);
    }



    /**
     * Customise the check() method.
     *
     * @param callable $callIfSuccess handler to call if function returns true.
     * @param callable $callIfFail    handler to call if function returns true.
     */
    public function check($callIfSuccess = null, $callIfFail = null)
    {
      return parent::check([$this, 'callbackSuccess'], [$this, 'callbackFail']);
    }



    /**
     * Callback for login-button.
     *
     */
    public function callbackLogin()
    {
        $this->AddOutput("<p><i>Försöker logga in som {$this->Value('acronym')}.</i></p>");
        $res = $this->di->users->login($this->Value('acronym'), $this->Value('password'));
        return $res;
        //$this->saveInSession = true;
    }


    /**
     * Callback for logout-button.
     *
     */
    public function callbackLogout()
    {
        $this->AddOutput("<p><i>Försöker logga ut.</i></p>");
        $res = $this->di->users->logout();
        return $res;
        //$this->saveInSession = true;
    }

    
    /**
     * Callback for submit-button.
     *
     */
    public function callbackSubmitFail()
    {
        $this->AddOutput("<p><i>FEL!</i></p>");
        $this->saveInSession = true;
        return false;
    }


    /**
     * Callback What to do if the form was submitted?
     *
     */
    public function callbackSuccess()
    {
        //$this->AddOUtput("<p><i>Användaruppgifter sparade</i></p>");
        $this->AddOUtput("Inloggad.");
        $this->redirectTo('users/login');
    }


    /**
     * Callback What to do when form could not be processed?
     *
     */
    public function callbackFail()
    {
        $this->AddOutput("<p><i>Något gick fel.</i></p>");
        $this->saveInSession = true;
        $this->redirectTo('users/login');
    }
}
