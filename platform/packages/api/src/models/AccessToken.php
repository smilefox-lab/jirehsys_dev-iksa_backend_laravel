<?php

namespace Botble\Api\Models;

use Botble\ACL\Http\Resources\CompanyResource;
use Botble\ACL\Http\Resources\RoleResource;
use Botble\ACL\Http\Resources\UserLoginResource;
use Botble\ACL\Models\User;
use Laravel\Passport\Bridge\AccessToken as PassportAccessToken;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Key;
use Lcobucci\JWT\Signer\Rsa\Sha256;
use League\OAuth2\Server\CryptKey;

class AccessToken extends PassportAccessToken
{

    private $privateKey;

    public function convertToJWT(CryptKey $privateKey)
    {
        $builder = new Builder();
        $user = User::find($this->getUserIdentifier());
        $builder
            ->setAudience($this->getClient()->getIdentifier())
            ->setId($this->getIdentifier(), true)
            ->setIssuedAt(time())
            ->setNotBefore(time())
            ->setExpiration($this->getExpiryDateTime()->getTimestamp())
            ->setSubject($this->getUserIdentifier())
            ->set('scopes', $this->getScopes())
            ->set('data', new UserLoginResource($user));
        return $builder
            ->getToken(new Sha256(), new Key($privateKey->getKeyPath(), $privateKey->getPassPhrase()));
    }

    public function setPrivateKey(CryptKey $privateKey)
    {
        $this->privateKey = $privateKey;
    }

    public function __toString()
    {
        return (string) $this->convertToJWT($this->privateKey);
    }

}
