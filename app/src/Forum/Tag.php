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
}