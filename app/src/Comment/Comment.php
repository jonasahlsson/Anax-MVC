<?php
namespace Joah\Comment;
 
/**
 * Model for Comments.
 *
 */
class Comment extends \Anax\MVC\CDatabaseModel
{
    
    // remove all comments from a page
    public function removePage($page) {
        
        $this->db->delete(
            $table = $this->getSource(),
            'page = ?'
            );
            
        return $this->db->execute([$page]);
        
        // redirecta
        $this->redirectTo("comment/index/$page");
    }

}