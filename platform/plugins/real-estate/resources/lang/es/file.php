<?php

use Botble\RealEstate\Enums\FileCategoryEnum;

return [
    'name'   => 'Archivos',
    'create' => 'Nuevo archivos',
    'edit'   => 'Editar archivos',


    'categories' => [
        FileCategoryEnum::TECHNICAL => 'Técnico',
        FileCategoryEnum::LEGAL     => 'Legal',
        FileCategoryEnum::PLAN      => 'Planos',
    ],
];
