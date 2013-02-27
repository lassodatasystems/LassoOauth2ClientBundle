#LassoOauth2ClientBundle

We needed to authenticate against an Oauth2 API using simple 2-legged Bearer-Token
authentication. But most of the Oauth2 clients we found were written for 3-legged
authentication, or provided minimal functionality, didn't integrate with Symfony
well or had other drawbacks.

We decided to write a very simple wrapper around the popular Buzz Browser using
the [Buzz Browser Bundle](https://github.com/juliendidier/BuzzBundle). Our
client simply retrieves an oauth2 access token and injects it into the header of requests
and hands them off to the Buzz Browser.

##Usage

Pass your authentication credentials into the constructor of the Token class. It
also needs a reference to a normal instance of the buzz browser to retrieve a
token.

Then pass the token instance and an instance of the buzz browser to the Client
class. You can use the client class like the buzz browser, with all public
functions being available.

##Drawbacks

Only the Bearer-Token authentication is supported, as it fulfills our needs - but
it's a very simple and less secure method than the HMAC authentication scheme also
supported by oauth2. An extension of this bundle to support HMAC would be nice,
but is not a high priority for us.

Three-legged authentication is not supported and not the intend of this bundle. If
you wish to use three legged authentication, you should probably look at other
clients. If you insist on using this bundle, you would have to implement your own
authentication mechanism to retrieve an authentication token. You could then write
your own Token class which has the public method 'getToken()', and pass that into
the client constructor.

[![Build Status](https://travis-ci.org/lassodatasystems/LassoOauth2ClientBundle.png?branch=master)](https://travis-ci.org/lassodatasystems/LassoOauth2ClientBundle)
