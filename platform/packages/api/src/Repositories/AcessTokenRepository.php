<?php

namespace Botble\Api\Repository;

use Botble\Api\Models\AccessToken;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use Laravel\Passport\Bridge\AccessTokenRepository;

class CustomAccessTokenRepository extends AccessTokenRepository
{
    public function getNewToken(ClientEntityInterface $clientEntity, array $scopes, $userIdentifier = null)
    {
        return new AccessToken($userIdentifier, $scopes, $clientEntity);
    }
}
