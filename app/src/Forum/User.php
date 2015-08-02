<?php
namespace Joah\Forum;
 
/**
 * Model for Users of the forum.
 *
 */
class User extends \Anax\Users\User
{
 
 
     /**
     * Find users orderd by acronym and return all.
     *
     * @return array
     */
    public function findAllByAcroym($order)
    {
        $this->db->select()
                 ->from($this->getSource());
        $this->orderBy("acronym $order");
     
        $this->db->execute();
        $this->db->setFetchModeClass(__CLASS__);
        return $this->db->fetchAll();
    }
 
    /**
     *  Fetch a user Gravatar
     *  
     *  @return string
     */
    public function fetchGravatar($id, $size = 40) {
    $user = $this->find($id);    
    return '<img src="http://www.gravatar.com/avatar/' . md5( strtolower( trim( $user->email ) ) ) . '?d=mm&s=' . $size . '">';
}
 
}