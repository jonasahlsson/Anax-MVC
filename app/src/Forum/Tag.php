<?php
namespace Joah\Forum;
 
/**
 * Model for Tags.
 *
 */
class Tag extends \Anax\MVC\CDatabaseModel
{
    /**
     * Find and return tags belonging to question_id.
     *
     * @return array
     */
    public function fetchTags($question_id)
    {
        $sql = "SELECT * FROM tag2question INNER JOIN tag ON tag2question.tag_id = tag.id 
            WHERE question_id = ?"; 
        $params = [$question_id];
        
        //$this->db->setFetchMode(\PDO::FETCH_ASSOC);
        return $this->db->executeFetchAll($sql, $params);
        //Dump($test);
        
        
    }
}