<?php
namespace Joah\Forum;
 
/**
 * Model for Votes.
 *
 */
class Vote extends \Anax\MVC\CDatabaseModel
{

    /*
    *  Fetch vote sum
    *
    */
    public function showVoteSum($type, $id)
    {
        // $this->db->setVerbose();
        // fetch sum 
        $sql = "SELECT sum(points) AS vote_sum
                FROM {$this->getSource()}
                WHERE points IS NOT NULL AND vote_type = ? AND vote_on = ?";
                
        $params = [$type, $id];
        
        $res = $this->db->executeFetchAll($sql, $params);
        
        // set empty to 0
        $res[0]->vote_sum = empty($res[0]->vote_sum) ? 0 :$res[0]->vote_sum;
        
        return $res[0]->vote_sum;
    }


    /*
    *  Vote
    *
    *  @param array $values, with keys vote_type, vote_on and user_id. Ex ['vote_type' => 1, 'vote_on' => 1, 'user_id' => 1, 'points' => 1]
    *
    *  @return void
    *
    */
    public function vote($values)
    {
    
        // fetch pervious vote if any
        $this->fetchVote($values);
        
        // replace with voting new info        
        $this->setProperties($values);
        
        // fetch values, if there was a previous vote info from there was fetched and replaced with new 
        $values = $this->getProperties();
        
        // update or create a new vote
        if (isset($values['id'])) {
            return $this->update($values);
        } else {
            return $this->create($values);
        }

        
    }

    
    /*
    *  Vote
    *   
    * $param array $values, with keys vote_type, vote_on and user_id. Example ['vote_type' => 1, 'vote_on' => 1, 'user_id' => 1]
    *
    * @return void
    */
    public function fetchVote($values)
    {
        // $this->db->setVerbose();
        // fetch vote
        $sql = "SELECT *
                FROM {$this->getSource()}
                WHERE vote_type = ? AND vote_on = ? AND user_id = ?";
                
        $params = [$values['vote_type'], $values['vote_on'], $values['user_id']];
        
        // $res = $this->db->executeFetchAll($sql, $params);
        $this->db->execute($sql, $params);
        $this->db->fetchInto($this);
    }
    
}