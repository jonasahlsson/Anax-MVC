<?php
namespace Anax\Users;
 
/**
 * Model for Users.
 *
 */
class User extends \Anax\MVC\CDatabaseModel
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
 
}