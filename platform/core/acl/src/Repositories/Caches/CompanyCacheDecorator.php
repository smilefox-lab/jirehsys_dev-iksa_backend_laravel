<?php

namespace Botble\ACL\Repositories\Caches;

use Botble\ACL\Repositories\Interfaces\CompanyInterface;
use Botble\Support\Repositories\Caches\CacheAbstractDecorator;

class CompanyCacheDecorator extends CacheAbstractDecorator implements CompanyInterface
{
    /**
     * {@inheritDoc}
     */
    public function getUserCompany(array $condition = [])
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }
}
