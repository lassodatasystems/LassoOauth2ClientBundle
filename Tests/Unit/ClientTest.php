<?php
namespace Lasso\Oauth2ClientBundle\Tests;

require dirname(__FILE__) . '/../../Client.php';
require 'MockBuzz.php';

use Lasso\Oauth2ClientBundle\Client;
use PHPUnit_Framework_TestCase;
use PHPUnit_Framework_MockObject_MockObject;

class ClientTest extends PHPUnit_Framework_TestCase
{
    protected $token;

    protected function setUp()
    {
        $this->token = $this->getMockBuilder('Lasso\Oauth2ClientBundle\Token')
            ->setMethods(['getToken'])
            ->disableOriginalConstructor()
            ->getMock();
        $this->token->expects($this->any())
            ->method('getToken')
            ->will($this->returnValue('test_token'));
    }

    protected function createBrowserMockThatExpectsHttpMethod($method)
    {
        $browser = $this->getMock('Buzz\Browser', ['call']);
        $browser->expects($this->once())
            ->method('call')
            ->with(
                $this->equalTo('example.com'),
                $this->equalTo($method),
                $this->equalTo(['X-Test: true', 'Authorization: Bearer test_token']),
                $this->isEmpty()
            );

        return $browser;
    }

    /**
     * @test
     */
    public function httpMethodWrappersShouldCallWithCorrectHttpMethod()
    {
        foreach (['GET', 'POST', 'HEAD', 'PATCH', 'PUT', 'DELETE'] as $method) {
            $browser = $this->createBrowserMockThatExpectsHttpMethod($method);

            $client = new Client($this->token, $browser);
            call_user_func_array([$client, $method], ['example.com', ['X-Test: true']]);
        }
    }

    /**
     * @test
     */
    public function callShouldAddAnAuthorizedHeader()
    {
        $browser = $this->createBrowserMockThatExpectsHttpMethod('post');
        $client = new Client($this->token, $browser);

        $client->call('example.com', 'post', ['X-Test: true']);
    }

    /**
     * @test
     */
    public function submitAddsAnAuthorizedHeader()
    {
        $browser = $this->getMock('Buzz\Browser', ['submit']);
        $browser->expects($this->once())
            ->method('submit')
            ->with(
                $this->equalTo('example.com'),
                $this->equalTo(['one' => '1']),
                $this->equalTo('post'),
                $this->equalTo(['X-Test: true', 'Authorization: Bearer test_token'])
            );

        $client = new Client($this->token, $browser);

        $client->submit('example.com', ['one' => '1'], 'post', ['X-Test: true']);
    }

    /**
     * @test
     */
    public function sendAddsAuthorizationHeaderLineToHeaders()
    {
        $request = $this->getMockForAbstractClass('Buzz\Message\RequestInterface', ['addHeader']);
        $request->expects($this->once())
            ->method('addHeader')
            ->with('Authorization: Bearer test_token');

        $response = $this->getMock('Buzz\Message\MessageInterface');

        $browser = $this->getMock('Buzz\Browser', ['send']);
        $browser->expects($this->once())
            ->method('send')
            ->with(
                $this->equalTo($request),
                $this->equalTo($response)
            );

        $client = new Client($this->token, $browser);
        $client->send($request, $response);
    }

    /**
     * @test
     */
    public function makeSureThatNonExistantFunctionsAreProxiedToTheBuzzBrowser()
    {
        $browser = $this->getMock('Buzz\Browser', ['non_existant_function']);
        $browser->expects($this->once())
            ->method('non_existant_function');

        $client = new Client($this->token, $browser);
        $client->non_existant_function();
    }
}
