<?php
namespace Lasso\Oauth2ClientBundle;

use Buzz\Browser;

/**
 * Class Token
 * @package Lasso\Oauth2ClientBundle
 */
class Token
{
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

        $response = $this->browser->get($url)->getContent();
        $response = json_decode($response, true);

        return $response['access_token'];
    }

    /**
     * Lazily loads an authorization token.
     *
     * @return string
     */
    public function getToken()
    {
        if (empty($this->token)) {
            $this->token = $this->acquireToken();
        }

        return $this->token;
    }
}
