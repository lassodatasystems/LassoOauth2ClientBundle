<?php
namespace Lasso\Oauth2ClientBundle\Tests;

require_once dirname(__FILE__) . '/../../TokenFactory.php';

use Lasso\Oauth2ClientBundle\TokenFactory;
use PHPUnit_Framework_TestCase;
use PHPUnit_Framework_MockObject_MockObject;
use StdClass;

class TokenFactoryTest extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function configurationChangesAfterFunctionCalls()
    {
        $defaults               = new StdClass();
        $defaults->clientId     = 'client_id';
        $defaults->clientSecret = 'client_secret';
        $defaults->tokenUrl     = 'token_url';
        $defaults->browser      = 'browser';

        $changed               = new StdClass();
        $changed->clientId     = 'new_client_id';
        $changed->clientSecret = 'new_client_secret';
        $changed->tokenUrl     = 'new_token_url';
        $changed->browser      = 'browser';

        $tokenFactory = new TokenFactory(
            $defaults->clientId,
            $defaults->clientSecret,
            $defaults->tokenUrl,
            $defaults->browser
        );

        $tokenFactory->withClientId($changed->clientId)
            ->withClientSecret($changed->clientSecret)
            ->withTokenUrl($changed->tokenUrl);

        $this->assertAttributeEquals($changed, 'tokenConfig', $tokenFactory);
        $this->assertAttributeEquals($defaults, 'originalTokenConfig', $tokenFactory);

        $tokenFactory->reset();

        $this->assertAttributeEquals($defaults, 'tokenConfig', $tokenFactory);
    }

    /**
     * @test
     */
    public function tokenFactoryReturnsTokenAndResetsToDefaultConfig()
    {
        $browser = $this->getMock('Buzz\Browser');

        $defaults               = new StdClass();
        $defaults->clientId     = 'client_id';
        $defaults->clientSecret = 'client_secret';
        $defaults->tokenUrl     = 'token_url';
        $defaults->browser      = $browser;

        $tokenFactory = new TokenFactory(
            $defaults->clientId,
            $defaults->clientSecret,
            $defaults->tokenUrl,
            $defaults->browser
        );

        $tokenFactory->withTokenUrl('new_token_url');

        $token = $tokenFactory->create();

        $this->assertAttributeEquals($defaults->clientId, 'clientId', $token);
        $this->assertAttributeEquals($defaults->clientSecret, 'clientSecret', $token);
        $this->assertAttributeEquals('new_token_url', 'tokenUrl', $token);

        $this->assertAttributeEquals($defaults, 'tokenConfig', $tokenFactory);
    }
}
