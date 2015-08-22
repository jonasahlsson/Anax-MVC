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
    *  fetch vote 
    *   
    * $param array $values, with keys vote_type, vote_on and user_id. Example ['vote_type' => 1, 'vote_on' => 1, 'user_id' => 1]
    *
    * @return void
    */
    public function fetchVote($values)
    {
        // fetch vote
        $sql = "SELECT *
                FROM {$this->getSource()}
                WHERE vote_type = ? AND vote_on = ? AND user_id = ?";
                
        $params = [$values['vote_type'], $values['vote_on'], $values['user_id']];
        
        // $res = $this->db->executeFetchAll($sql, $params);
        $this->db->execute($sql, $params);
        $this->db->fetchInto($this);
    }
    
    /*
    *  Fetch votes by user
    *   
    * $param int user_id
    *
    * @return array
    */
    public function fetchVotesByUser($user_id)
    {
        // count votes
        $sql = "SELECT count(user_id) AS vote_count
                FROM {$this->getSource()}
                WHERE user_id = ?";
                
        $params = [$user_id];
        
        $res = $this->db->executeFetchAll($sql, $params);
        return $res;
    }
    
    
    /*
    *  Fetch votes by user
    *   
    * $param int user_id
    *
    * @return array
    */
    public function countPosNegVotesByUser($user_id)
    {
        // count positive and negative votes separately
        $sql = "SELECT sum(CASE WHEN points = 1 THEN 1 ELSE 0 END) AS pos_votes,
                sum(CASE WHEN points = -1 THEN 1 ELSE 0 END) AS neg_votes,
                sum(points) AS sum
                FROM vote
                WHERE user_id = ?";
                
        $params = [$user_id];
        
        $res = $this->db->executeFetchAll($sql, $params);
        return $res;
    }
    
    /*
    *  Fetch votes on question/answer/comment
    *   
    * $params int vote_type, int vote_on 
    *
    * @return array int
    */
    public function fetchVotesOn($vote_type, $vote_on)
    {
        // count votes
        $sql = "SELECT points
                FROM {$this->getSource()}
                WHERE vote_type = ? AND vote_on = ?";
                
        $params = [$vote_type, $vote_on];
        
        $res = $this->db->executeFetchAll($sql, $params);
        return $res;
    }
    
}