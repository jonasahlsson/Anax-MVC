<?php
namespace Joah\Forum;
 
/**
 * Model for Answers.
 *
 */
class Answer extends \Anax\MVC\CDatabaseModel
{
    /**
     * Find and return answers belonging to question_id.
     *
     * @return array
     */
    public function findAnswers($question_id)
    {
        $this->db->select()
                ->from($this->getSource())
                ->where('question_id = ?');
                
        $this->db->execute([$question_id]);
        $this->db->setFetchModeClass(__CLASS__);
        return $this->db->fetchAll();
    }
    
    /**
     * Find and return answers by user
     *
     * @return array
     */
    public function findAnswerByUser($user_id)
    {
        $this->db->select()
                ->from($this->getSource())
                ->where('user_id = ?');
                
        $this->db->execute([$user_id]);
        $this->db->setFetchModeClass(__CLASS__);
        return $this->db->fetchAll();
    }
    
    /**
     * Find users with most answers
     *
     * @return array
     */
    public function activeUsers($num = null)
    {
        // $this->db->setVerbose();
        
        $sql = "SELECT user_id, count(user_id) AS count_user_id
                FROM {$this->getSource()}
                GROUP BY user_id
                ORDER BY count_user_id DESC
                LIMIT $num;";
        
        $params = [];
        
        return $this->db->executeFetchAll($sql, $params);
    }
    
    
}