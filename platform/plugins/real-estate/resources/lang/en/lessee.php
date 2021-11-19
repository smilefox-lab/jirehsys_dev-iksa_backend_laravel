<?php

use Botble\RealEstate\Enums\LesseeTypeEnum;

return [
    'name'   => 'Lessees',
    'create' => 'New lessee',
    'edit'   => 'Edit lessee',
    'form'  => [
        'name'            => 'name',
        'rut'             => 'RUT',
        'rut_placeholder' => 'RUT',
        'email'           => 'email',
        'phone'           => 'phone',
        'type'            => 'Type',
        'select_type'     => 'Select type...'
    ],
    'table'  => [
        'rut'             => 'RUT',
    ],

    'type' => [
        LesseeTypeEnum::NATURAL  => 'Natural',
        LesseeTypeEnum::LEGAL    => 'Legal',
    ],
];
