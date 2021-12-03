<?php

namespace Imgur\Api;

use Imgur\Exception\MissingArgumentException;

/**
 * CRUD for Comment.
 *
 * @see https://api.imgur.com/endpoints/comment
 *
 * @author Adrian Ghiuta <adrian.ghiuta@gmail.com>
 */
class Comment extends AbstractApi
{
    /**
     * Get information about a specific comment.
     *
     * @param string $commentId
     *
     * @see https://api.imgur.com/endpoints/comment#comment
     *
     * @return array Comment (@see https://api.imgur.com/endpoints/gallery#gallery-comments)
     */
    public function comment($commentId)
    {
        return $this->get('comment/' . $commentId);
    }

    /**
     * Creates a new comment, returns the ID of the comment.
     *
     * @param array $data
     *
     * @see https://api.imgur.com/endpoints/comment#comment-create
     *
     * @return array (@see https://api.imgur.com/models/basic)
     */
    public function create($data)
    {
        if (!isset($data['image_id'], $data['comment'])) {
            throw new MissingArgumentException(['image_id', 'comment']);
        }

        return $this->post('comment', $data);
    }

    /**
     * Delete a comment by the given id.
     *
     * @param string $commentId
     *
     * @see https://api.imgur.com/endpoints/comment#comment-delete
     *
     * @return array (@see https://api.imgur.com/models/basic)
     */
    public function deleteComment($commentId)
    {
        return $this->delete('comment/' . $commentId);
    }

    /**
     * Get the comment with all of the replies for the comment.
     *
     * @param string $commentId
     *
     * @see https://api.imgur.com/endpoints/comment#comment-replies
     *
     * @return array Comment (@see https://api.imgur.com/endpoints/gallery#gallery-comments)
     */
    public function replies($commentId)
    {
        return $this->get('comment/' . $commentId . '/replied');
    }

    /**
     * Create a reply for the given comment.
     *
     * @param string $commentId
     * @param array  $data
     *
     * @see https://api.imgur.com/endpoints/comment#comment-reply-create
     *
     * @return array (@see https://api.imgur.com/models/basic)
     */
    public function createReply($commentId, $data)
    {
        if (!isset($data['image_id'], $data['comment'])) {
            throw new MissingArgumentException(['image_id', 'comment']);
        }

        return $this->post('comment/' . $commentId, $data);
    }

    /**
     * Vote on a comment. The $vote variable can only be set as "up" or "down".
     *
     * @param string $commentId
     * @param string $vote
     *
     * @see https://api.imgur.com/endpoints/comment#comment-vote
     *
     * @return array (@see https://api.imgur.com/models/basic)
     */
    public function vote($commentId, $vote)
    {
        $this->validateVoteArgument($vote, ['up', 'down']);

        return $this->post('comment/' . $commentId . '/vote/' . $vote);
    }

    /**
     * Report a comment for being inappropriate.
     *
     * @param string $commentId
     *
     * @see https://api.imgur.com/endpoints/comment#comment-report
     *
     * @return array (@see https://api.imgur.com/models/basic)
     */
    public function report($commentId)
    {
        return $this->post('comment/' . $commentId . '/report');
    }
}
