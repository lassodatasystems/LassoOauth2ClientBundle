<?php
namespace Lasso\Oauth2ClientBundle;

use OAuth2\Client;

class Factory
{
    public static function get($clientId, $clientSecret, $tokenUrl)
    {
        $client = new OAuth2\Client(
            $clientId,
            $clientSecret
        );

        $token = $client->getAccessToken(
            $tokenUrl,
            'client_credentials',
            []
        )['result']['access_token'];

        $client->setAccessToken($token);

        return $client;
    }
}
