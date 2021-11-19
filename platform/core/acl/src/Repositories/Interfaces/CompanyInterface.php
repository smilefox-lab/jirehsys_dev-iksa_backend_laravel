<?php

namespace Botble\ACL\Repositories\Interfaces;

use Botble\Support\Repositories\Interfaces\RepositoryInterface;

interface CompanyInterface extends RepositoryInterface
{
    /**
     * @param array $condition
     * @return array
     */
    public function getUserCompany(array $condition = []);
}
