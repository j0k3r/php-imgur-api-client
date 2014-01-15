<?php

namespace Imgur\Api;

use Imgur\Api\AbstractApi;

/**
 * CRUD for Accounts
 * 
 * @link https://api.imgur.com/endpoints/account
 * @author Adrian Ghiuta <adrian.ghiuta@gmail.com>
 */

class Account extends AbstractApi {
    
    public function base($username = 'me') {
        
        return $this->get('account/'.$username);
    }
    
}