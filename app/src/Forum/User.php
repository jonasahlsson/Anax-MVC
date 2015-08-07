<?php
namespace Joah\Forum;
 
/**
 * Model for Users of the forum.
 *
 */
class User extends \Anax\Users\User
{
 
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
}   