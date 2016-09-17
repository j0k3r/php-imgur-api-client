<?php

namespace Imgur\Auth;

/**
 * Class used to handle user authentication.
 *
 * @author Adrian Ghiuta <adrian.ghiuta@gmail.com>
 */
interface AuthInterface
{
    /**
     * Build the authentication URL.
     *
     * @param string $responseType (code, token, pin) Determines if Imgur should return an authorization_code, a PIN code, or an opaque access_token
     * @param string $state        Any value which you want Imgur to pass back
     *
     * @return string
     */
    public function getAuthenticationUrl($responseType = 'code', $state = null);

    public function getAccessToken();
    public function requestAccessToken($code, $responseType);
    public function setAccessToken($accessToken);

    public function sign();

    public function refreshToken();
    public function checkAccessTokenExpired();
}
