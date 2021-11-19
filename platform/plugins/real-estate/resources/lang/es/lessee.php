<?php

use Botble\RealEstate\Enums\LesseeTypeEnum;

return [
    'name'   => 'Arrendatarios',
    'create' => 'Nuevo arrendatario',
    'edit'   => 'Editar arrendatario',
    'form'  => [
        'name'            => 'Nombre',
        'rut'             => 'RUT',
        'rut_placeholder' => 'RUT',
        'email'           => 'email',
        'phone'           => 'Teléfono',
        'type'            => 'Tipo',
        'contact_name'    => 'Nombre del contacto',
        'select_type'     => 'Seleccionar tipo...'
    ],
    'table'  => [
        'rut'             => 'RUT',
    ],

    'type' => [
        LesseeTypeEnum::NATURAL  => 'Natural',
        LesseeTypeEnum::LEGAL    => 'Legal',
    ],
];
