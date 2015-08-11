<?php
namespace Joah\Forum;
 
/**
 * Model for Users of the forum.
 *
 */
class User extends \Anax\Users\User
{
    const SESSION_VARIABLE = 'user';
    
    /**
     *  Fetch a user Gravatar
     *  
     *  @return string
     */
    public function fetchGravatar($id, $size = 40) 
    {
        $user = $this->find($id);    
        return '<img src="http://www.gravatar.com/avatar/' . md5( strtolower( trim( $user->email ) ) ) . '?d=mm&s=' . $size . '">';
    }
 
    /**
     *  Fetch username
     *  
     *  @return string
     */
    public function fetchName($id)
    {
        $user = $this->find($id);
        return $user->name;
    }

    
    /**
     *  Login
     */
    public function login($acronym, $password)
    {
        //verify password
        $res = $this->verifyPassword($acronym, $password);

        // put user data in session
        if($res) {
        
            
            $this->session->set(self::SESSION_VARIABLE, [
                                'acronym' => $this->acronym, 
                                'name' => $this->name,
                                'id' => $this->id
                                ]);
        return true;
        }
        
        else {
            return false;
        }
        
    }
    
    
    /**
     *  logout
     */
    
    public function logout() 
    {
        //unset session variable and return true 
        // if(isset($_SESSION['user'])) {
        if($this->session->has(self::SESSION_VARIABLE)) {
            $this->session->set(self::SESSION_VARIABLE, []);
            // unset($_SESSION['user']);
            return true;
        }
        //if session variable missing noone was logged in. return false
            else {
            return false;
            }
    }
    
    
    /**
     *  Fetch user by acronym
     */
    public function fetchByAcronym($acronym)
    {
        $this->db->select()
                 ->from($this->getSource())
                 ->where("acronym = ?");
        
        $this->db->execute([$acronym]);
        return $this->db->fetchInto($this);
    }
    
    /**
    *  Verify password.
    */
    public function verifyPassword($acronym, $password)
    {
        //fetch user
        $user = $this->fetchByAcronym($acronym);

        // no such user.
        if (empty($user)) {
            return false;
        }
        
        // verify password
        if(password_verify($password, $this->password)){
            return true;
        }
        else {
            return false;
        }        
    }    
    
    /**
     *  Verify that user is logged in.
     */
    public function isLoggedIn()
    {
        // check session for user id
        if(!isset($_SESSION['user']['id'])) {
            return true;
        }
        else {
            return false;
        }
    }
    
    /**
     *  Verify that user is logged as an id
     */
    
    public function verifyLoggedInAs($user_id)
    {
         if (isset($_SESSION['user']['id']) AND $_SESSION['user']['id'] === $user_id) {
            return true;
        }
        else {
            return false;
        }
    }    
}   