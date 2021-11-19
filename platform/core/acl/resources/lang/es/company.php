<?php

use Botble\ACL\Enums\CompanyStatusEnum;

return [
    'name'       => 'Empresas',
    'create'     => 'Nueva empresa',
    'edit'       => 'Editar empresa',
    'statuses'   => [
        CompanyStatusEnum::ACTIVATED   => 'Habilitado',
        CompanyStatusEnum::DEACTIVATED => 'Deshabilitado',
    ],
    'form'        => [
        'label'               => 'Empresa',
        'name'                => 'Nombre',
        'name_placeholder'    => 'Nombre',
        'status'              => 'Estatus',
        'rut'                 => 'RUT',
        'rut_placeholder'     => 'RUT',
        'address'             => 'Dirección',
        'address_placeholder' => 'Dirección',
        'phone'               => 'Teléfono',
        'phone_placeholder'   => 'Teléfono',
        'files'               => 'Archivos',
    ],

    'select_company'      => 'Seleccionar empresa',
    'no_company_assigned' => 'No tiene empresa asignada',
    'permissions' => [
        'create'  => 'Crear',
        'edit'    => 'Editar',
        'destroy' => 'Eliminar',
    ]
];
