<?php
namespace Lasso\Oauth2ClientBundle;

use PHPUnit_Framework_TestCase;
use PHPUnit_Framework_MockObject_MockObject;

require dirname(__FILE__) . '/../../Token.php';

/**
 * Class TokenTest
 *
 * @package Lasso\Oauth2ClientBundle
 */
class TokenTest extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function getTokenBuildsCorrectQueryAndReturnsOnlyTheToken()
    {
        $response = $this->getMock('Buzz\Message\Response', ['getContent']);
        $response->expects($this->once())
            ->method('getContent')
            ->will($this->returnValue('{"access_token":"test_token"}'));

        $browser = $this->getMock('Buzz\Browser', ['get']);
        $browser->expects($this->once())
            ->method('get')
            ->with($this->equalTo(
                'http://www.example.com?'
                . 'grant_type=client_credentials'
                . '&client_id=test_id'
                . '&client_secret=test_secret'))
            ->will($this->returnValue($response));

        $token = new Token(
            'test_id',
            'test_secret',
            'http://www.example.com',
            $browser);

        $this->assertEquals('test_token', $token->getToken());
    }
}
