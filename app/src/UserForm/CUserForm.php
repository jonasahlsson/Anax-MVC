<?php

namespace Joah\UserForm;

/**
 * Anax base class for wrapping sessions.
 *
 */
class CUserForm extends \Mos\HTMLForm\CForm
{
    use \Anax\DI\TInjectionaware,
        \Anax\MVC\TRedirectHelpers;

    /**
     * Constructor
     *
     */
    public function __construct($user = null)
    {
        
        $acronym = isset($user->acronym) ? htmlentities($user->acronym) : null;
        $name = isset($user->name) ? htmlentities($user->name) : null;
        $email = isset($user->email) ? htmlentities($user->email) : null;
        $id = isset($user->id) ? htmlentities($user->id) : null;

        parent::__construct([], [
            'acronym' => [
                'type'        => 'text',
                'label'       => 'Akronym:',
                'value'       => $acronym,
                'required'    => true,
                'validation'  => ['not_empty'],
            ],
            'name' => [
                'type'        => 'text',
                'label'       => 'Namn:',
                'value'       => $name,
                'required'    => true,
                'validation'  => ['not_empty'],
            ],
            
            'password' => [
                'type'        => 'password',
                'label'       => 'Lösenord:',
                'required'    => true,
                'validation'  => ['not_empty'],
            ],
            
            'email' => [
                'type'        => 'text',
                'required'    => true,
                'label'       => 'e-post',
                'value'       => $email,
                'validation'  => ['not_empty', 'email_adress'],
            ],
            'spara' => [
                'type'      => 'submit',
                'callback'  => [$this, 'callbackSubmit'],
            ],
            'submit-fail' => [
                'type'      => 'submit',
                'callback'  => [$this, 'callbackSubmitFail'],
            ],
            
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
     * Callback for submit-button.
     *
     */
    public function callbackSubmit()
    {
        
        //$this->Value('password'),
        $this->di->UsersController->saveUser(
            $this->Value('acronym'),
            $this->Value('name'),
            $this->Value('password'),
            $this->Value('email')
        );

        //$this->saveInSession = true;
        
        return true;
    }



    /**
     * Callback for submit-button.
     *
     */
    public function callbackSubmitFail()
    {
        $this->AddOutput("<p><i>Användaruppgifter ej sparade.</i></p>");
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
        //$this->AddOUtput($this->Value('acronym'));
        $this->redirectTo('users');
    }



    /**
     * Callback What to do when form could not be processed?
     *
     */
    public function callbackFail()
    {
        $this->AddOutput("<p><i>Något gick fel.</i></p>");
        $this->saveInSession = true;

        $this->redirectTo();
    }
}
