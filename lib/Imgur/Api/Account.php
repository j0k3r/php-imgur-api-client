<?php

namespace Imgur\Api;

/**
 * CRUD for Accounts.
 *
 * @link https://api.imgur.com/endpoints/account
 *
 * @author Adrian Ghiuta <adrian.ghiuta@gmail.com>
 */
class Account extends AbstractApi
{
    /**
     * Request standard user information.
     *
     * @param string $username
     *
     * @link https://api.imgur.com/endpoints/account#account
     *
     * @return array Account (@see https://api.imgur.com/models/account)
     */
    public function base($username = 'me')
    {
        return $this->get('account/' . $username);
    }

    /**
     * UNDOCUMENTED
     * Delete a user account, you can only access this if you're logged in as the user.
     *
     * @param string $username
     *
     * @return bool
     */
    public function deleteAccount($username)
    {
        return $this->delete('account/' . $username);
    }

    /**
     * Return the images the user has favorited in the gallery.
     *
     * @param string $username
     * @param int    $page
     * @param string $sort     'oldest', or 'newest'. Defaults to 'newest'
     *
     * @link https://api.imgur.com/endpoints/account#account-gallery-favorites
     *
     * @return array Gallery Image (@see https://api.imgur.com/models/gallery_image) OR Gallery Album (@see https://api.imgur.com/models/gallery_album)
     */
    public function galleryFavorites($username = 'me', $page = 0, $sort = 'newest')
    {
        $this->validateSortArgument($sort, ['oldest', 'newest']);

        return $this->get('account/' . $username . '/gallery_favorites/' . (int) $page . '/' . $sort);
    }

    /**
     * Returns the users favorited images, only accessible if you're logged in as the user.
     *
     * @param string $username
     *
     * @link https://api.imgur.com/endpoints/account#account-favorites
     *
     * @return array Gallery Image (@see https://api.imgur.com/models/gallery_image) OR Gallery Album (@see https://api.imgur.com/models/gallery_album)
     */
    public function favorites($username = 'me')
    {
        return $this->get('account/' . $username . '/favorites');
    }

    /**
     * Return the images a user has submitted to the gallery.
     *
     * @param string $username
     * @param int    $page
     *
     * @link https://api.imgur.com/endpoints/account#account-submissions
     *
     * @return array Gallery Image (@see https://api.imgur.com/models/gallery_image) OR Gallery Album (@see https://api.imgur.com/models/gallery_album)
     */
    public function submissions($username = 'me', $page = 0)
    {
        return $this->get('account/' . $username . '/submissions/' . (int) $page);
    }

    /**
     * Returns the account settings, only accessible if you're logged in as the user.
     *
     * @param string $username
     *
     * @link https://api.imgur.com/endpoints/account#account-settings
     *
     * @return array Account Settings (@see https://api.imgur.com/models/account_settings)
     */
    public function settings($username = 'me')
    {
        return $this->get('account/' . $username . '/settings');
    }

    /**
     * Updates the account settings for a given user, the user must be logged in.
     *
     * @param array $parameters
     *
     * @link https://api.imgur.com/endpoints/account#update-settings
     *
     * @return bool
     */
    public function changeAccountSettings($parameters)
    {
        return $this->post('account/me/settings', $parameters);
    }

    /**
     * UNDOCUMENTED
     * Return the statistics about the account.
     *
     * @param string $username
     *
     * @return array
     */
    public function accountStats($username = 'me')
    {
        return $this->get('account/' . $username . '/stats');
    }

    /**
     * Returns the totals for the gallery profile.
     *
     * @param string $username
     *
     * @link https://api.imgur.com/endpoints/account#account-profile
     *
     * @return array Gallery Profile (@see https://api.imgur.com/models/gallery_profile)
     */
    public function accountGalleryProfile($username = 'me')
    {
        return $this->get('account/' . $username . '/gallery_profile');
    }

    /**
     * Checks to see if user has verified their email address.
     *
     * @param string $username
     *
     * @link https://api.imgur.com/endpoints/account#verify-email
     *
     * @return bool
     */
    public function verifyUsersEmail($username = 'me')
    {
        return $this->get('account/' . $username . '/verifyemail');
    }

    /**
     * Sends an email to the user to verify that their email is valid to upload to gallery. Must be logged in as the user to send.
     *
     * @param string $username
     *
     * @link https://api.imgur.com/endpoints/account#send-verify-email
     *
     * @return bool
     */
    public function sendVerificationEmail($username = 'me')
    {
        return $this->post('account/' . $username . '/verifyemail');
    }

    /**
     * Get all the albums associated with the account. Must be logged in as the user to see secret and hidden albums.
     *
     * @param string $username
     * @param int    $page
     *
     * @link https://api.imgur.com/endpoints/account#albums
     *
     * @return array Array of Album (@see https://api.imgur.com/models/album)
     */
    public function albums($username = 'me', $page = 0)
    {
        return $this->get('account/' . $username . '/albums/' . (int) $page);
    }

    /**
     * Get additional information about an album, this endpoint works the same as the Album Endpoint.
     * You can also use any of the additional routes that are used on an album in the album endpoint.
     *
     * @param string $username
     * @param string $albumId
     *
     * @link https://api.imgur.com/endpoints/account#album
     *
     * @return array Album (@see https://api.imgur.com/models/album)
     */
    public function album($albumId, $username = 'me')
    {
        return $this->get('account/' . $username . '/album/' . $albumId);
    }

    /**
     * Return an array of all of the album IDs.
     *
     * @param string $username
     * @param int    $page
     *
     * @link https://api.imgur.com/endpoints/account#album-ids
     *
     * @return array<int>
     */
    public function albumIds($username = 'me', $page = 0)
    {
        return $this->get('account/' . $username . '/albums/ids/' . (int) $page);
    }

    /**
     * Return the total number of albums associated with the account.
     *
     * @param string $username
     *
     * @link https://api.imgur.com/endpoints/account#album-count
     *
     * @return int
     */
    public function albumCount($username = 'me')
    {
        return $this->get('account/' . $username . '/albums/count');
    }

    /**
     * Delete an Album with a given id.
     *
     * @param string $username
     * @param string $albumId
     *
     * @link https://api.imgur.com/endpoints/account#album-delete
     *
     * @return bool
     */
    public function albumDelete($albumId, $username = 'me')
    {
        return $this->delete('account/' . $username . '/album/' . $albumId);
    }

    /**
     * Return the comments the user has created.
     *
     * @param string $username
     * @param int    $page
     * @param string $sort     'best', 'worst', 'oldest', or 'newest'. Defaults to 'newest'
     *
     * @link https://api.imgur.com/endpoints/account#comments
     *
     * @return array Array of Comment (@see https://api.imgur.com/models/comment)
     */
    public function comments($username = 'me', $page = 0, $sort = 'newest')
    {
        $this->validateSortArgument($sort, ['best', 'worst', 'oldest', 'newest']);

        return $this->get('account/' . $username . '/comments/' . $sort . '/' . (int) $page);
    }

    /**
     * Return information about a specific comment. This endpoint works the same as the Comment Endpoint.
     * You can use any of the additional actions that the comment endpoint allows on this end point.
     *
     * @param string $commentId
     * @param string $username
     *
     * @link https://api.imgur.com/endpoints/account#comment
     *
     * @return array Comment (@see https://api.imgur.com/models/comment)
     */
    public function comment($commentId, $username = 'me')
    {
        return $this->get('account/' . $username . '/comment/' . $commentId);
    }

    /**
     * Return an array of all of the comment IDs.
     *
     * @param string $username
     * @param int    $page
     * @param string $sort     'best', 'worst', 'oldest', or 'newest'. Defaults to 'newest'
     *
     * @link https://api.imgur.com/endpoints/account#comment-ids
     *
     * @return array<int>
     */
    public function commentIds($username = 'me', $page = 0, $sort = 'newest')
    {
        $this->validateSortArgument($sort, ['best', 'worst', 'oldest', 'newest']);

        return $this->get('account/' . $username . '/comments/ids/' . $sort . '/' . (int) $page);
    }

    /**
     * Return a count of all of the comments associated with the account.
     *
     * @param string $username
     *
     * @link https://api.imgur.com/endpoints/account#comment-count
     *
     * @return int
     */
    public function commentCount($username = 'me')
    {
        return $this->get('account/' . $username . '/comments/count');
    }

    /**
     * Delete a comment. You are required to be logged in as the user whom created the comment.
     *
     * @param string $commentId
     * @param string $username
     *
     * @link https://api.imgur.com/endpoints/account#comment-delete
     *
     * @return bool
     */
    public function commentDelete($commentId, $username = 'me')
    {
        return $this->delete('account/' . $username . '/comment/' . $commentId);
    }

    /**
     * Return all of the images associated with the account.
     * You can page through the images by setting the page, this defaults to 0.
     *
     * @param string $username
     * @param int    $page
     *
     * @link https://api.imgur.com/endpoints/account#images
     *
     * @return array Array of Image (@see https://api.imgur.com/models/image)
     */
    public function images($username = 'me', $page = 0)
    {
        return $this->get('account/' . $username . '/images/' . (int) $page);
    }

    /**
     * Return information about a specific image.
     * This endpoint works the same as the Image Endpoint. You can use any of the additional actions that the image endpoint with this endpoint.
     *
     * @param string $imageId
     * @param string $username
     *
     * @link https://api.imgur.com/endpoints/account#image
     *
     * @return array Image (@see https://api.imgur.com/models/image)
     */
    public function image($imageId, $username = 'me')
    {
        return $this->get('account/' . $username . '/image/' . $imageId);
    }

    /**
     * Returns an array of Image IDs that are associated with the account.
     *
     * @param string $username
     * @param int    $page
     *
     * @link https://api.imgur.com/endpoints/account#image-ids
     *
     * @return array<int>
     */
    public function imageIds($username = 'me', $page = 0)
    {
        return $this->get('account/' . $username . '/images/ids/' . (int) $page);
    }

    /**
     * Returns the total number of images associated with the account.
     *
     * @param string $username
     *
     * @link https://api.imgur.com/endpoints/account#image-count
     *
     * @return int
     */
    public function imageCount($username = 'me')
    {
        return $this->get('account/' . $username . '/images/count');
    }

    /**
     * Deletes an Image. This requires a delete hash rather than an ID.
     *
     * @param string $deleteHash
     * @param string $username
     *
     * @link https://api.imgur.com/endpoints/account#image-delete
     *
     * @return bool
     */
    public function imageDelete($deleteHash, $username = 'me')
    {
        return $this->delete('account/' . $username . '/image/' . $deleteHash);
    }

    /**
     * Returns all of the reply notifications for the user. Required to be logged in as that user.
     *
     * @param string $username
     * @param bool   $onlyNew
     *
     * @link https://api.imgur.com/endpoints/account#replies
     *
     * @return array Array of Notification (@see https://api.imgur.com/models/notification)
     */
    public function replies($username = 'me', $onlyNew = false)
    {
        $onlyNew = $onlyNew ? 'true' : 'false';

        return $this->get('account/' . $username . '/notifications/replies', ['new' => $onlyNew]);
    }
}
