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
     */
    public function getAuthenticationUrl(string $responseType = 'code', string $state = null): string;

    public function getAccessToken(): ?array;

    public function requestAccessToken(string $code, string $requestType = null): array;

    public function setAccessToken(array $accessToken): void;

    public function sign(): void;

    public function refreshToken(): array;

    public function checkAccessTokenExpired(): bool;
}
