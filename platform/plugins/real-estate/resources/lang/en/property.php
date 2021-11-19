<?php

use Botble\RealEstate\Enums\ModerationStatusEnum;
use Botble\RealEstate\Enums\PropertyPeriodEnum;
use Botble\RealEstate\Enums\PropertyStatusEnum;

return [
    'name'     => 'Propiedades',
    'create'   => 'Nuevo inmueble',
    'edit'     => 'Editar inmueble',
    'form'     => [
        'main_info'               => 'Información general',
        'basic_info'              => 'Información básica',
        'name'                    => 'Título',
        'type'                    => 'Tipo',
        'select_type'             => 'Seleccionar tipo',
        'images'                  => 'Imagenes',
        'files'                   => 'Archivos',
        'button_add_image'        => 'Agregar imagenes',
        'location'                => 'Dirección',
        'number_bedroom'          => 'Número habitaciones',
        'number_bathroom'         => 'Número de baños',
        'number_floor'            => 'Número pisos',
        'square'                  => 'M2 Terreno',
        'square_build'            => 'M2 Construidos',
        'price'                   => 'Valor',
        'features'                => 'Características',
        'project'                 => 'Proyectos',
        'date'                    => 'Información de la fecha',
        'currency'                => 'Moneda',
        'commune'                 => 'Comuna',
        'period'                  => 'Periodo',
        'destiny'                 => 'Destino',
        'select_destiny'          => 'Seleccionar tipo',
        'role'                    => 'Rol',
        'leaves'                  => 'Fojas',
        'number'                  => 'N°',
        'year'                    => 'Año',
        'placeholder_year'        => 'Ej: 2002',
        'buy'                     => 'Compra',
        'date_deed'               => 'Fecha de escritura',
        'appraisal'               => 'Avalúo',
        'pesos'                   => 'Pesos',
        'uf'                      => 'UF',
        'coordinates'             => 'Coordenadas',
        'coordinates_placeholder' => 'Seleccione un punto en el mapa',
        'profitability'           => 'Rentabilidad',
        'technical'               => 'Documentos Técnicos',
        'legal'                   => 'Documentos Legales',
        'plane'                   => 'Planos',
    ],

    'table'    => [
        'date_deed' => 'Date Deed'
    ],

    'statuses' => [
        PropertyStatusEnum::AVAILABLE => 'Disponible',
        PropertyStatusEnum::RENTED    => 'Arrendada',
    ],

    'periods'  => [
        PropertyPeriodEnum::DAY   => 'Día',
        PropertyPeriodEnum::MONTH => 'Mes',
        PropertyPeriodEnum::YEAR  => 'Año',
    ],

    'moderation_status'   => 'Moderation status',
    'moderation-statuses' => [
        ModerationStatusEnum::PENDING  => 'Pending',
        ModerationStatusEnum::APPROVED => 'Approved',
        ModerationStatusEnum::REJECTED => 'Rejected',
    ],
];
