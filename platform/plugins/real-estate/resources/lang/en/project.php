<?php

use Botble\RealEstate\Enums\ProjectStatusEnum;

return [
    'name'     => 'Projects',
    'create'   => 'New project',
    'edit'     => 'Edit project',
    'form'     => [
        'general_info' => 'General information',
        'name'         => 'Name',
        'description'  => 'Description',
        'basic_info'   => 'Basic information',
        'location'     => 'Location',
        'investor'     => 'Investor',
        'number_block' => 'Number blocks',
        'number_floor' => 'Number floors',
        'number_flat'  => 'Number flats',
        'date_finish'  => 'Finish date',
        'date_sell'    => 'Open sell date',
        'images'       => 'Images',
        'price_from'   => 'Lowest price',
        'price_to'     => 'Max price',
        'currency'     => 'Currency',
        'city'         => 'City',
        'category' => 'Category',
    ],
    'statuses' => [
        ProjectStatusEnum::NOT_AVAILABLE => 'Not available',
        ProjectStatusEnum::PRE_SALE      => 'Preparing selling',
        ProjectStatusEnum::SELLING       => 'Selling',
        ProjectStatusEnum::SOLD          => 'Sold',
        ProjectStatusEnum::BUILDING      => 'Building',
    ],
];
