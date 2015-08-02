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

}