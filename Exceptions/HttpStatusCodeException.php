<?php

namespace Lasso\Oauth2ClientBundle\Exceptions;

use Buzz\Message\Response;
use Exception;

/**
 * Class HttpStatusCodeException
 * @package Lasso\Oauth2ClientBundle\Exceptions
 */
abstract class HttpStatusCodeException extends Exception
{
    /**
     * @var Response
     */
    protected $response;

    /**
     * @param Response $response
     */
    public function __construct(Response $response)
    {
        $this->response = $response;

        $this->code    = $this->response->getStatusCode();
        $this->message = $this->response->getContent();
    }

    /**
     * @return Response
     */
    public function getResponse()
    {
        return $this->response;
    }
}
