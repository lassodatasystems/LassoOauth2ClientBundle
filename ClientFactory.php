<?php
namespace Lasso\Oauth2ClientBundle;

use Lasso\Oauth2ClientBundle\Token;
use Lasso\Oauth2ClientBundle\TokenFactory;
use Lasso\Oauth2ClientBundle\Client;
use Buzz\Browser;

class ClientFactory
{
    protected $tokenFactory;
    protected $browser;

    public function __construct(
        TokenFactory $tokenFactory,
        Browser $browser
    )
    {
        $this->tokenFactory = $tokenFactory;
        $this->browser      = $browser;
    }

    public function withClientId($clientId)
    {
        $this->tokenFactory->withClientId($clientId);

        return $this;
    }

    public function withClientSecret($clientSecret)
    {
        $this->tokenFactory->withClientSecret($clientSecret);

        return $this;
    }

    public function withTokenUrl($tokenUrl)
    {
        $this->tokenFactory->withTokenUrl($tokenUrl);

        return $this;
    }

    public function reset()
    {
        $this->tokenFactory->reset();
    }

    /**
     * @return Client
     */
    public function create()
    {
        $client = new Client($this->tokenFactory->create(), $this->browser);
        $this->tokenFactory->reset();

        return $client;
    }
}
