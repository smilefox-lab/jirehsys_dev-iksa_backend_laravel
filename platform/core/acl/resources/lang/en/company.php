<?php

use Botble\ACL\Enums\CompanyStatusEnum;

return [
    'name'        => 'Companies',
    'create'      => 'New company',
    'edit'        => 'Edit company',
    'statuses'    => [
        CompanyStatusEnum::ACTIVATED   => 'Activated',
        CompanyStatusEnum::DEACTIVATED => 'Deactivated',
    ],
    'form'        => [
        'label'               => 'Company',
        'name'                => 'Name',
        'name_placeholder'    => 'Name',
        'status'              => 'Status',
        'rut'                 => 'RUT',
        'rut_placeholder'     => 'RUT',
        'files'               => 'Files',
        'address'             => 'Address',
        'address_placeholder' => 'Address',
        'phone'               => 'Phone',
        'phone_placeholder'   => 'Phone',
    ],
    'select_company'      => 'Select company',
    'no_company_assigned' => 'No company assigned',
    'permissions' => [
        'create'  => 'Crear',
        'edit'    => 'Editar',
        'destroy' => 'Eliminar',
    ]
];
