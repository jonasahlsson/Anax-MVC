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
    
    /**
     *   Output tags as a string
     *  
     *  @return string
     */
    public function tagsToString($question_id)
    {
        // fetch array og tag objects
        $tags = $this->fetchTags($question_id);
        
        //turn objects into a string. tags separated by #. #tag1 #tag2
        $tagString = "";
        foreach($tags as $tag) {
            $tagString .= "#";
            $tagString .= $tag->tag_text;
            $tagString .= " ";
        }
        return $tagString;
    }
    
    /**
     *  Turns a string of tags into an array of tags
     *  
     *  @return array returns an array of tags
     */
    public function tagStringToArray($string)
    {
        // explode on # and trim
        $array = array_map('trim', (explode('#', $string)));
        
        // get rid of first which is empty if #was used, and also removes string if # was forgotten
        $array = array_slice($array, 1);
        
        // remove null, false, and empty strings
        $array = array_filter( $array, 'strlen' );

        // make it lower case
        //$array = array_map('mb_strtolower', $array);
        
        return $array;
    }

    
    /**
     * count tags
     *
     * @return array of tag object
     */
    public function countTags()
    {
        // find tags, count them, order by popularity
        $sql = "SELECT tag_id, tag_text, count(*) as count FROM tag2question INNER JOIN tag ON tag2question.tag_id = tag.id 
            GROUP BY tag_id
            ORDER BY count DESC"; 
        $params = [];
        
        //$this->db->setFetchMode(\PDO::FETCH_ASSOC);
        return $this->db->executeFetchAll($sql, $params);
    }    
    
    /**
     *  Find question by tags
     *  
     *  @return array 
     */
    public function findQuestionByTag($tag_id)
    {
    // find questions associated with a tag_id
        $sql = "SELECT t.tag_text, t2q.question_id, q.title, q.content, q.user_id, q.timestamp, t2q.tag_id 
            FROM tag as t
            LEFT OUTER JOIN tag2question AS t2q
            ON t2q.tag_id = t.id
            LEFT OUTER JOIN question as q
            ON t2q.question_id = q.id
            WHERE t2q.tag_id = ?
            ORDER BY q.id DESC; "; 
        $params = [$tag_id];
        
        return $this->db->executeFetchAll($sql, $params);
    }
    
    
    /**
     *  Find tags by question_id
     *  
     *  @return array 
     */
    public function findTagByQuestion($question_id)
    {
    // find tags associated with a question
        $sql = "SELECT t.tag_text, t2q.question_id, t2q.tag_id 
            FROM tag as t
            LEFT OUTER JOIN tag2question AS t2q
            ON t2q.tag_id = t.id
            LEFT OUTER JOIN question as q
            ON t2q.question_id = q.id
            WHERE t2q.question_id = ?
            ORDER BY q.id DESC; "; 
        $params = [$question_id];
        
        return $this->db->executeFetchAll($sql, $params);
    }
    
    
    /**
     *  Fetch name of tag
     *  
     *  @return string
     */
    public function fetchTagText($id)
    {
        $tag = $this->find($id);
        return $tag->tag_text;
    }
}