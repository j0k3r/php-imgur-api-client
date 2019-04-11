<?php

namespace Imgur\Api;

/**
 * CRUD for Memegen.
 *
 * @see https://api.imgur.com/endpoints/memegen
 *
 * @author Adrian Ghiuta <adrian.ghiuta@gmail.com>
 */
class Memegen extends AbstractApi
{
    /**
     * Get the list of default memes.
     *
     * @see https://api.imgur.com/endpoints/memegen#defaults
     *
     * @return array Of images (@see Image.php)
     */
    public function defaultMemes()
    {
        return $this->get('memegen/defaults');
    }
}
