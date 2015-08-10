<?php

namespace Joah\UserForm;

/**
 * A form for adding editing User data
 *
 */
class CUserForm extends \Mos\HTMLForm\CForm
{
    use \Anax\DI\TInjectionaware,
        \Anax\MVC\TRedirectHelpers;
    
    private $user;

    

    
    
    
    /**
     * Constructor
     *
     */
    public function __construct($user = null)
    {

        $this->user = $user;
        $acronym = isset($user->acronym) ? htmlentities($user->acronym) : null;
        $name = isset($user->name) ? htmlentities($user->name) : null;
        $password = isset($user->password) ? htmlentities($user->password) : null;
        $email = isset($user->email) ? htmlentities($user->email) : null;
        $id = isset($user->id) ? htmlentities($user->id) : null;
        $created = isset($user->created) ? htmlentities($user->created) : null;

        parent::__construct([], [
            'id' => [
                'type'        => 'hidden',
                'label'       => 'ID:',
                'value'       => $id,
            ],
            'acronym' => [
                'type'        => 'text',
                'label'       => 'Användarnamn:',
                'value'       => $acronym,
                'required'    => true,
                // allow only letters, numbers, ' ', '-' and '_'
                'validation' => array('not_empty', 'custom_test' => array('message' => 'Please use only letters and numbers.', 'test' => 'return ctype_alnum(str_replace(array(" ","-","_"), "", $value));')),
                // 'validation'  => ['not_empty'] 
                // 'validation'  => ['not_empty', 'alphaNumeric'],
                // 'validation' => array('not_empty', 'custom_test' => array('message' => 'Please use only letters and numbers.', 'test' => 'testForAlphaNumeric')),
                // 'validation' => array('not_empty', 'custom_test' => array('message' => 'Please use only letters and numbers.', 'test' => 'return ctype_alnum($value);')),
                
            ],
            'name' => [
                'type'        => 'text',
                'label'       => 'Namn:',
                'value'       => $name,
                'required'    => true,
                // 'validation'  => ['not_empty'] 
                // allow only letters, numbers, ' ', '-' and '_'
                'validation' => array('not_empty', 'custom_test' => array('message' => 'Please use only letters and numbers.', 'test' => 'return ctype_alnum(str_replace(array(" ","-","_"), "", $value));')),
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
            'created' => [
                'type'        => 'hidden',
                'value'       => $created,
            ],

            'spara' => [
                'type'      => 'submit',
                'callback'  => [$this, 'callbackSubmit'],
            ],
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
     * Callback for submit-button.
     *
     */
    public function callbackSubmit()
    {
        
        // save if empty id which makes a new user or save if id exist and password verifies
        if (empty($this->Value('id')) OR (password_verify($this->Value('password'), $this->user->password))){
            //$this->Value('password'),
            
            // set created if empty
            $now = gmdate('Y-m-d H:i:s');
            $created = !empty($this->Value('created')) ? $this->Value('created') : $now;
            
            // save
            $this->di->UsersController->saveUser(
                $this->Value('acronym'),
                $this->Value('name'),
                $this->Value('password'),
                $this->Value('email'),
                $created
            );
    
            //$this->saveInSession = true;
            
            return true;
        }        
        else { 
        $this->AddOutput("<p><i>Fel lösenord.</i></p>");
            return false;
        }
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
    
        
    public function testForAlphaNumeric($string)
    {
        // return ctype_alnum($string);
        return true;
    }
    
    
}
