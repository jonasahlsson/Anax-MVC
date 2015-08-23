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
    public function findAnswers($question_id, $sortAnswersBy = null)
    {
        
       // set sort order for answers
        $sortAnswersBy = (isset($sortAnswersBy) AND $sortAnswersBy === "date" )? "timestamp ASC" : "points DESC";
        
        // $this->db->setVerbose();
        $this->db->select()
                ->from($this->getSource())
                ->where('question_id = ?')
                ->join('vote', 'answer.id = vote.vote_on AND ( vote.vote_type = 2 OR vote_type IS NULL)', 'LEFT OUTER')
                ->orderBy($sortAnswersBy);
                
        $this->db->execute([$question_id]);
        $this->db->setFetchModeClass(__CLASS__);
        return $this->db->fetchAll();
    }
    
    /**
     * Find and return answers belonging to question_id.
     *
     * @return array
     */
    public function findAnswersWithVotes($question_id, $sortAnswersBy = null)
    {
        
       // set sort order for answers
        $sortAnswersBy = (isset($sortAnswersBy) AND $sortAnswersBy === "date" )? "timestamp ASC" : "sum_points DESC";
        
        
            $sql = "SELECT *, answer.id AS answer_id, answer.user_id AS user_id, sum(vote.points) AS sum_points FROM answer
                    LEFT OUTER JOIN vote ON answer.id = vote.vote_on AND (vote_type = 2 OR vote_type IS NULL)
                    WHERE question_id = ?
                    GROUP BY answer.id
                    ORDER BY $sortAnswersBy";
        
        $params = [$question_id];
        
        $res = $this->db->executeFetchAll($sql, $params);

        return $res;
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
     * Find questions answered by user
     *
     * @return array
     */
    public function findAnsweredQuestions($user_id)
    {
        $sql = "SELECT answer.*, question.title
                FROM answer
                INNER JOIN question ON answer.question_id = question.id
                WHERE answer.user_id = ?
                GROUP BY question_id";
        $params = [$user_id]        ;
                
        return $this->db->executeFetchAll($sql, $params);
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
    
    /**
     * Count answers by question
     *
     * @return array
     */
    public function countAnswersByQuestion($question_id)
    {
        // $this->db->setVerbose();
        
        $sql = "SELECT question_id, count(question_id) AS count_answer
                FROM {$this->getSource()}
                WHERE question_id = ? ;";
        
        $params = [$question_id];
        
        return $this->db->executeFetchAll($sql, $params);
    }
}