<?php

use Botble\Location\Enums\DefaultStatusEnum;

return [
    'name'       => 'Locations',
    'create'     => 'New location',
    'edit'       => 'Edit location',
    'all_states' => 'All states',

    'statuses' => [
        DefaultStatusEnum::ENABLED  => 'Enabled',
        DefaultStatusEnum::DISABLED => 'Disabled',
    ],
];
