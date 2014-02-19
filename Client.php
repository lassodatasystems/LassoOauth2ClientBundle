<?php
namespace Lasso\Oauth2ClientBundle;

use Lasso\Oauth2ClientBundle;
use Buzz\Browser;
use Buzz\Message\RequestInterface;
use Buzz\Message\MessageInterface;
use Buzz\Message\Response;
use Lasso\Oauth2ClientBundle\Exceptions\ClientErrorException;
use Lasso\Oauth2ClientBundle\Exceptions\ServerErrorException;

/**
 * This class essentially mirrors the public functions on Buzz\Browser,
 * but injects an authorization token in the headers where appropriate.
 * This way you still have access to all the functionality in Buzz\Browser,
 * and don't have to manage oauth tokens manually. Also, if your IDE
 * supports type hinting, you can hint instances of this class with
 * Buzz\Browser to get auto-completion on all available methods.
 *
 * Class Client
 * @package Lasso\Oauth2ClientBundle
 */
class Client
{
    /**
     * @var Token
     */
    protected $token;

    /**
     * @var Browser
     */
    protected $browser;

    /**
     * @param Token   $token
     * @param Browser $browser
     */
    public function __construct(Token $token, Browser $browser)
    {
        $this->token   = $token;
        $this->browser = $browser;
    }

    /**
     * Adds authorization token to headers using bearer method
     *
     * @param $headers
     *
     * @return array
     */
    protected function patchHeaders($headers)
    {
        $headers[] = 'Authorization: Bearer ' . $this->token->getToken();

        return $headers;
    }

    /**
     * @see Lasso\Oauth2ClientBundle\Client::call
     *
     * @param       $url
     * @param array $headers
     *
     * @return Response
     */
    public function get($url, $headers = array())
    {
        return $this->call($url, RequestInterface::METHOD_GET, $headers);
    }

    /**
     * @see Lasso\Oauth2ClientBundle\Client::call
     *
     * @param        $url
     * @param array  $headers
     * @param string $content
     *
     * @return Response
     */
    public function post($url, $headers = array(), $content = '')
    {
        return $this->call($url, RequestInterface::METHOD_POST, $headers, $content);
    }

    /**
     * @see Lasso\Oauth2ClientBundle\Client::call
     *
     * @param       $url
     * @param array $headers
     *
     * @return Response
     */
    public function head($url, $headers = array())
    {
        return $this->call($url, RequestInterface::METHOD_HEAD, $headers);
    }

    /**
     * @see Lasso\Oauth2ClientBundle\Client::call
     *
     * @param        $url
     * @param array  $headers
     * @param string $content
     *
     * @return Response
     */
    public function patch($url, $headers = array(), $content = '')
    {
        return $this->call($url, RequestInterface::METHOD_PATCH, $headers, $content);
    }

    /**
     * @see Lasso\Oauth2ClientBundle\Client::call
     *
     * @param        $url
     * @param array  $headers
     * @param string $content
     *
     * @return Response
     */
    public function put($url, $headers = array(), $content = '')
    {
        return $this->call($url, RequestInterface::METHOD_PUT, $headers, $content);
    }

    /**
     * @see Lasso\Oauth2ClientBundle\Client::call
     *
     * @param        $url
     * @param array  $headers
     * @param string $content
     *
     * @return Response
     */
    public function delete($url, $headers = array(), $content = '')
    {
        return $this->call($url, RequestInterface::METHOD_DELETE, $headers, $content);
    }

    /**
     * Sends a http request with the given method. Is wrapped by helper methods in this
     * class that
     *
     * @param string $url     The URL to call
     * @param string $method  The request method to use
     * @param array  $headers An array of request headers
     * @param string $content The request content
     *
     * @throws Exceptions\ClientErrorException
     * @throws Exceptions\ServerErrorException
     * @return Response The response object
     */
    public function call($url, $method, $headers = array(), $content = '')
    {
        $headers = $this->patchHeaders($headers);

        $response = $this->browser->call($url, $method, $headers, $content);

        switch (true) {
            case (400 <= $response->getStatusCode() && $response->getStatusCode() <= 499):
                throw new ClientErrorException($response);
                break;
            case (500 <= $response->getStatusCode() && $response->getStatusCode() <= 599):
                throw new ServerErrorException($response);
                break;
        }

        return $response;
    }

    /**
     * Sends a form request.
     *
     * @param string $url     The URL to submit to
     * @param array  $fields  An array of fields
     * @param string $method  The request method to use
     * @param array  $headers An array of request headers
     *
     * @return Response The response object
     */
    public function submit($url, array $fields, $method = RequestInterface::METHOD_POST, $headers = array())
    {
        $headers = $this->patchHeaders($headers);

        return $this->browser->submit($url, $fields, $method, $headers);
    }

    /**
     * Sends a request.
     *
     * @param RequestInterface $request  A request object
     * @param MessageInterface $response A response object
     *
     * @return MessageInterface The response
     */
    public function send(RequestInterface $request, MessageInterface $response = null)
    {
        $request->addHeader('Authorization: Bearer ' . $this->token->getToken());

        return $this->browser->send($request, $response);
    }

    /**
     * Proxy all calls that don't require injecting an Authorization-header
     * to the browser instance.
     *
     * @param string $name      The called methods name
     * @param array  $arguments The arguments the method should be called with
     *
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        return call_user_func_array([$this->browser, $name], $arguments);
    }
}
