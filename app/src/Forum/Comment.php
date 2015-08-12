<?php
namespace Joah\Forum;
 
/**
 * Model for Comments.
 *
 */
class Comment extends \Anax\MVC\CDatabaseModel
{
    
    /**
     * Find and return comments belonging to questions.
     *
     * @return array
     */
    public function findQuestionComments($question_id)
    {
        
        // $this->db->setVerbose(); 
        $this->db->select()
                ->from($this->getSource())
                ->where('question_id = ?')
                ->andWhere('answer_id is NULL');
                
        $this->db->execute([$question_id]);
        $this->db->setFetchModeClass(__CLASS__);
        return $this->db->fetchAll();
    }

    
    /**
     * Find and return comments belonging to answers.
     *
     * @return array
     */
    public function findAnswerComments($question_id)
    {
        
        // $this->db->setVerbose(); 
        $this->db->select()
                ->from($this->getSource())
                ->where('question_id = ?')
                ->andWhere('answer_id is NOT NULL');
                
        $this->db->execute([$question_id]);
        $this->db->setFetchModeClass(__CLASS__);
        return $this->db->fetchAll();
    }
    
    /**
     * Find and return comments made by user
     *
     * @return array
     */
    public function findCommentByUser($user_id)
    {
        $this->db->select()
                ->from($this->getSource())
                ->where('user_id = ?');
                
        $this->db->execute([$user_id]);
        $this->db->setFetchModeClass(__CLASS__);
        return $this->db->fetchAll();
    }
    
    /**
     * Find users with most comments
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