<?php
namespace Lasso\Oauth2ClientBundle;

use Buzz\Browser;

class Token
{
    protected $clientId;
    protected $clientSecret;
    protected $tokenUrl;
    protected $browser;
    protected $token;

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

    protected function aquireToken()
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

    public function getToken()
    {
        if (empty($this->token)) {
            $this->token = $this->aquireToken();
        }

        return $this->token;
    }
}
