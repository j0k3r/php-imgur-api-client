<?php

namespace Imgur\Api;

use Imgur\Exception\MissingArgumentException;

/**
 * CRUD for Conversations.
 *
 * @see https://api.imgur.com/endpoints/conversation
 *
 * @author Adrian Ghiuta <adrian.ghiuta@gmail.com>
 */
class Conversation extends AbstractApi
{
    /**
     * Get list of all conversations for the logged in user.
     *
     * @see https://api.imgur.com/endpoints/conversation#conversation-list
     *
     * @return array Array of Conversation (@see https://api.imgur.com/models/conversation)
     */
    public function conversations()
    {
        return $this->get('conversations');
    }

    /**
     * Get information about a specific conversation. Includes messages.
     *
     * @param string $conversationId
     *
     * @see https://api.imgur.com/endpoints/conversation#conversation
     *
     * @return array Conversation (@see https://api.imgur.com/models/conversation)
     */
    public function conversation($conversationId)
    {
        return $this->get('conversations/' . $conversationId);
    }

    /**
     * Create a new message. Check the link for the structure of the $data
     * (current structure should contain the 'recipient' and 'body' keys ('recipient' being the username of the receiver)).
     *
     * @param array $data
     *
     * @see https://api.imgur.com/endpoints/conversation#message-create
     *
     * @return array (@see https://api.imgur.com/models/basic)
     */
    public function messageCreate($data)
    {
        if (!isset($data['recipient'], $data['body'])) {
            throw new MissingArgumentException(['recipient', 'body']);
        }

        return $this->post('conversations/' . $data['recipient'], $data);
    }

    /**
     * Delete a conversation by the given ID.
     *
     * @param string $conversationId
     *
     * @see https://api.imgur.com/endpoints/conversation#message-delete
     *
     * @return array (@see https://api.imgur.com/models/basic)
     */
    public function conversationDelete($conversationId)
    {
        return $this->delete('conversations/' . $conversationId);
    }

    /**
     * Report a user for sending messages that are against the Terms of Service.
     *
     * @param string $username
     *
     * @see https://api.imgur.com/endpoints/conversation#message-report
     *
     * @return array (@see https://api.imgur.com/models/basic)
     */
    public function reportSender($username)
    {
        return $this->post('conversations/report/' . $username);
    }

    /**
     * Report a user for sending messages that are against the Terms of Service.
     *
     * @param string $username
     *
     * @see https://api.imgur.com/endpoints/conversation#message-block
     *
     * @return array (@see https://api.imgur.com/models/basic)
     */
    public function blockSender($username)
    {
        return $this->post('conversations/block/' . $username);
    }
}
