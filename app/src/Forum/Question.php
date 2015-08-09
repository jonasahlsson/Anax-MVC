<?php
namespace Joah\Forum;
 
/**
 * Model for Questions.
 *
 */
class Question extends \Anax\MVC\CDatabaseModel
{
    /**
     * Find and return questions by user
     *
     * @return array
     */
    public function findQuestionByUser($user_id)
    {
        $this->db->select()
                ->from($this->getSource())
                ->where('user_id = ?');
                
        $this->db->execute([$user_id]);
        $this->db->setFetchModeClass(__CLASS__);
        return $this->db->fetchAll();
    }
}