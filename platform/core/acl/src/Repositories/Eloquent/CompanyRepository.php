<?php

namespace Botble\ACL\Repositories\Eloquent;

use Auth;
use Botble\ACL\Repositories\Interfaces\CompanyInterface;
use Botble\Support\Repositories\Eloquent\RepositoriesAbstract;

class CompanyRepository extends RepositoriesAbstract implements CompanyInterface
{
    /**
     * {@inheritDoc}
     */
    public function getUserCompany(array $condition = [])
    {
        $params = [
            'condition' => $condition,
            'order_by'  => ['companies.name' => 'ASC'],
        ];

        $this->model = $this->originalModel;

        if (!Auth::user()->inRole('admin') && !Auth::user()->isSuperUser()) {
            $this->model = $this->model->where('id', '=', Auth::user()->company_id);
        }


        return $this->advancedGet($params);
    }
}
