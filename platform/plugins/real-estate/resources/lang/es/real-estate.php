<?php

use Botble\RealEstate\Enums\DefaultStatusEnum;

return [
    'name'                   => 'Inmobiliaria',
    'settings'               => 'Configuración',
    'google_map'             => 'Google Map',
    'google_map_description' => 'Configuración de Google Map para buscar ubicación',
    'api_key'                => 'API key (opcional)',
    'api_key_helper'         => 'Ingrese google maps key',
    'import'                 => 'Importaciones',

    'statuses' => [
        DefaultStatusEnum::ENABLED  => 'Habilitado',
        DefaultStatusEnum::DISABLED => 'Deshabilitado',
    ],


];
