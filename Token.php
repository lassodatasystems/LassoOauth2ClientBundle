<?php
namespace Lasso\Oauth2ClientBundle;

use Buzz\Browser;

/**
 * Class Token
 * @package Lasso\Oauth2ClientBundle
 */
class Token
{
    const DEFAULT_EXPIRES_IN = 3600;

    /**
     * @var string
     */
    protected $clientId;

    /**
     * @var string
     */
    protected $clientSecret;

    /**
     * @var string
     */
    protected $tokenUrl;

    /**
     * @var Browser
     */
    protected $browser;

    /**
     * @var string
     */
    protected $token;

    /**
     * UTC Timestamp(number of seconds since the Unix Epoch)
     * of when token was last acquired.
     */
    protected $whenAcquired;

    /**
     * TTL of token in seconds.
     */
    protected $expires_in = Token::DEFAULT_EXPIRES_IN;

    /**
     * @param string  $clientId
     * @param string  $clientSecret
     * @param string  $tokenUrl
     * @param Browser $browser
     */
    public function __construct($clientId,
                                $clientSecret,
                                $tokenUrl,
                                Browser $browser)
    {
        $this->clientId     = $clientId;
        $this->clientSecret = $clientSecret;
        $this->tokenUrl     = $tokenUrl;
        $this->browser      = $browser;
    }

    /**
     *
     * @return string
     */
    protected function acquireToken()
    {
        $query = http_build_query([
            'grant_type'    => 'client_credentials',
            'client_id'     => $this->clientId,
            'client_secret' => $this->clientSecret
        ]);

        $url = $this->tokenUrl . '?' . $query;

        $response           = $this->browser->get($url)->getContent();
        $response           = json_decode($response, true);
        $this->whenAcquired = time();
        $this->expires_in   = isset($response['expires_in']) ? $response['expires_in'] : Token::DEFAULT_EXPIRES_IN;

        return $response['access_token'];
    }

    /**
     * Lazily loads an authorization token.
     *
     * @return string
     */
    public function getToken()
    {
        if (empty($this->token) ||
            (time() - $this->whenAcquired) >= $this->expires_in
        ) {
            $this->token = $this->acquireToken();
        }

        return $this->token;
    }
}
