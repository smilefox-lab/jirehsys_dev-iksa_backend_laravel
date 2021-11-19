<?php

namespace Botble\Api\Providers;

use Botble\Api\Repository\CustomAccessTokenRepository;
use Laravel\Passport\Bridge\ClientRepository;
use Laravel\Passport\Bridge\ScopeRepository;
use Laravel\Passport\PassportServiceProvider;
use League\OAuth2\Server\AuthorizationServer;

class CustomPassportServiceProvider extends PassportServiceProvider
{
    public function makeAuthorizationServer()
    {
        return new AuthorizationServer(
            $this->app->make(ClientRepository::class),
            $this->app->make(CustomAccessTokenRepository::class),
            $this->app->make(ScopeRepository::class),
            $this->makeCryptKey('private'),
            app('encrypter')->getKey()
        );
    }

}
