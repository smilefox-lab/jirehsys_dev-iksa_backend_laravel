<?php

use Botble\Location\Enums\DefaultStatusEnum;

return [
    'name'                   => 'Real Estate',
    'settings'               => 'Settings',
    'google_map'             => 'Google Map',
    'google_map_description' => 'Settings for Google Map to search location',
    'api_key'                => 'API key (optional)',
    'api_key_helper'         => 'Insert google maps key',
    'import'                 => 'Imports',

    'statuses' => [
        DefaultStatusEnum::ENABLED  => 'Enabled',
        DefaultStatusEnum::DISABLED => 'Disabled',
    ],
];
