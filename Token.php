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
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->tokenUrl = $tokenUrl;
        $this->browser = $browser;
    }

    protected function aquireToken()
    {
        $this->browser->get($this->tokenUrl)->getContent();
    }
}
