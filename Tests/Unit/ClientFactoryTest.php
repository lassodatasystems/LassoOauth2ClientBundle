<?php
namespace Lasso\Oauth2ClientBundle\Tests;

require_once dirname(__FILE__) . '/../../TokenFactory.php';
require_once dirname(__FILE__) . '/../../ClientFactory.php';

use Lasso\Oauth2ClientBundle\ClientFactory;
use PHPUnit_Framework_TestCase;
use PHPUnit_Framework_MockObject_MockObject;

class ClientFactoryTest extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function configurationValuesArePassedToTokenFactory()
    {
        $clientId     = 'test_client_id';
        $clientSecret = 'test_client_secret';
        $tokenUrl     = 'test_token_url';

        $tokenFactory = $this->getMockBuilder('Lasso\Oauth2ClientBundle\TokenFactory')
            ->disableOriginalConstructor()
            ->setMethods([
                'withClientId',
                'withClientSecret',
                'withTokenUrl',
                'reset'
            ])
            ->getMock();

        $tokenFactory->expects($this->once())
            ->method('withClientId')
            ->with($clientId);
        $tokenFactory->expects($this->once())
            ->method('withClientSecret')
            ->with($clientSecret);
        $tokenFactory->expects($this->once())
            ->method('withTokenUrl')
            ->with($tokenUrl);
        $tokenFactory->expects($this->once())
            ->method('reset');

        $browser = $this->getMock('Buzz\Browser');

        $clientFactory = new ClientFactory($tokenFactory, $browser);
        $clientFactory->withClientId($clientId)
            ->withClientSecret($clientSecret)
            ->withTokenUrl($tokenUrl)
            ->reset();
    }

    /**
     * @test
     */
    public function createClient()
    {
        $token   = $this->getMockBuilder('Lasso\Oauth2ClientBundle\Token')
            ->disableOriginalConstructor()
            ->getMock();
        $browser = $this->getMock('Buzz\Browser');

        $tokenFactory = $this->getMockBuilder('Lasso\Oauth2ClientBundle\TokenFactory')
            ->disableOriginalConstructor()
            ->setMethods(['create', 'reset'])
            ->getMock();

        $tokenFactory->expects($this->once())
            ->method('create')
            ->will($this->returnValue($token));
        $tokenFactory->expects($this->once())
            ->method('reset');

        $clientFactory = new ClientFactory($tokenFactory, $browser);
        $client        = $clientFactory->create();

        $this->assertInstanceOf('Lasso\Oauth2ClientBundle\Client', $client);
    }
}
