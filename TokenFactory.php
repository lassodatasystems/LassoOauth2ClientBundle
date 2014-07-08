<?php
namespace Lasso\Oauth2ClientBundle;

use Lasso\Oauth2ClientBundle\Token;
use Buzz\Browser;
use StdClass;

/**
 * Class TokenFactory
 *
 * @package Lasso\Oauth2ClientBundle
 */
class TokenFactory
{
    /**
     * The original configuration that was passed to the constructor
     *
     * @var \StdClass
     */
    protected $originalTokenConfig;

    /**
     * The current configuration that may have been altered from the
     * original configuration. Will be reset to the original configuration
     * after every 'create' call.
     *
     * @var \StdClass
     */
    protected $tokenConfig;
    protected $browser;

    /**
     * @param string  $clientId
     * @param string  $clientSecret
     * @param string  $tokenUrl
     * @param Browser $browser
     */
    public function __construct(
        $clientId,
        $clientSecret,
        $tokenUrl,
        $browser
    )
    {
        $this->originalTokenConfig = new StdClass();
        $this->originalTokenConfig->clientId     = $clientId;
        $this->originalTokenConfig->clientSecret = $clientSecret;
        $this->originalTokenConfig->tokenUrl     = $tokenUrl;
        $this->originalTokenConfig->browser      = $browser;

        $this->tokenConfig = clone $this->originalTokenConfig;
    }

    /**
     * @param string $clientId
     *
     * @return $this
     */
    public function withClientId($clientId)
    {
        $this->tokenConfig->clientId = $clientId;

        return $this;
    }

    /**
     * @param string $clientSecret
     *
     * @return $this
     */
    public function withClientSecret($clientSecret)
    {
        $this->tokenConfig->clientSecret = $clientSecret;

        return $this;
    }

    /**
     * @param string $tokenUrl
     *
     * @return $this
     */
    public function withTokenUrl($tokenUrl)
    {
        $this->tokenConfig->tokenUrl = $tokenUrl;

        return $this;
    }

    /**
     * Reset
     */
    public function reset()
    {
        $this->tokenConfig = clone $this->originalTokenConfig;
    }

    /**
     * @return Token
     */
    public function create()
    {
        $token = new Token(
            $this->tokenConfig->clientId,
            $this->tokenConfig->clientSecret,
            $this->tokenConfig->tokenUrl,
            $this->tokenConfig->browser
        );

        $this->reset();

        return $token;
    }
}
