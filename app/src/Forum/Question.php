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
    
    /**
     * Find latest questions
     *
     */
    public function latestQuestions($num = null)
    {
        // $this->db->setVerbose();
        
        $this->db->select()
                ->from($this->getSource())
                ->orderBy('timestamp')
                ->limit($num);
                
        $this->db->execute([]);
        $this->db->setFetchModeClass(__CLASS__);
        return $this->db->fetchAll();
    }
    
    
    /**
     * Find active askers
     *
     */
    public function activeAskers($num = null)
    {
        // $this->db->setVerbose();
        
        $sql = "SELECT user_id, count(user_id) AS count_user_id
                FROM {$this->getSource()}
                GROUP BY user_id
                ORDER BY count_user_id DESC
                LIMIT $num;";
        
        $params = [];
        
        return $this->db->executeFetchAll($sql, $params);
        
// SELECT user_id, count(user_id) AS count_user_id
                // FROM question
                // GROUP BY user_id
                // LIMIT 3;
                
        // $this->db->execute($params);
        // $this->db->setFetchModeClass(__CLASS__);
        // return $this->db->fetchAll();
    }
    
    
}