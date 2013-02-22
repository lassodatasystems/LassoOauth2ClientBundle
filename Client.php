<?php
namespace Lasso\Oauth2ClientBundle;


use Lasso\Oauth2ClientBundle;
use Buzz;


class Client
{
    protected $token;
    protected $browser;

    public function __construct(Token $token, Browser $browser)
    {
        $this->token = $token;
        $this->browser = $browser;
    }

    public function __call($name, $arguments)
    {
        return call_user_func_array([$this->browser, $name], $arguments);
    }
}
