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
}