<?php

namespace Imgur\Api;

use Imgur\Api\AbstractApi;

/**
 * CRUD for Comment
 * 
 * @link https://api.imgur.com/endpoints/comment
 * @author Adrian Ghiuta <adrian.ghiuta@gmail.com>
 */

class Comment extends AbstractApi {
    
    /**
     * Get information about a specific comment.
     *
     * @param string $commentId
     * @return \Imgur\Api\Model\Comment
     */
    public function comment($commentId) {
        $parameters = $this->get('comment/'.$commentId);
        
        return new Model\Comment($parameters);
    }
    
    /**
     * Creates a new comment, returns the ID of the comment.
     *
     * @param array $data
     * @return \Imgur\Api\Model\Basic
     */
    public function create($data) {
        $parameters = $this->post('comment', $data);
        
        return new Model\Basic($parameters);
    }
    
    /**
     * Delete a comment by the given id.
     * 
     * @param string $commentId
     * @return \Imgur\Api\Model\Basic
     */
    public function deleteComment($commentId) {
        $parameters = $this->delete('comment/'.$commentId);
        
        return new Model\Basic($parameters);
    }
    
    /**
     * Get the comment with all of the replies for the comment.
     *
     * @param string $commentId
     * @return \Imgur\Api\Model\Comment
     */
    public function replies($commentId) {
        $parameters = $this->get('comment/'.$commentId.'/replied');
        
        $replies = array();
        
        foreach($parameters['data'] as $parameter) {
            $replies[] = new Model\Comment($parameter['content']);
        }
        
        return $replies;
    }
    
    /**
     * Vote on a comment. The $vote variable can only be set as "up" or "down".
     *
     * @param string $commentId
     * @param string $vote
     * @return \Imgur\Api\Model\Basic
     */
    public function vote($commentId, $vote) {
        $parameters = $this->post('comment/'.$commentId.'/vote/'.$vote);
        
        return new Model\Basic($parameters);
    }
    
    /**
     * Report a comment for being inappropriate.
     *
     * @param string $commentId
     * @return \Imgur\Api\Model\Basic
     */
    public function report($commentId) {
        $parameters = $this->post('comment/'.$commentId.'/report');
        
        return new Model\Basic($parameters);
    }
    
    /**
     * Create a reply for the given comment.
     *
     * @param string $commentId
     * @param array $data
     * @return \Imgur\Api\Model\Basic
     */
    public function createReply($commentId, $data) {
        $parameters = $this->post('comment/'.$commentId, $data);
        
        return new Model\Basic($parameters);
    }
}