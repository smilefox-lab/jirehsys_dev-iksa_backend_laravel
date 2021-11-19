<?php

use Botble\RealEstate\Enums\FileCategoryEnum;

return [
    'name'   => 'Files',
    'create' => 'New files',
    'edit'   => 'Edit files',


    'files-categories' => [
        FileCategoryEnum::TECHNICAL => 'Technical',
        FileCategoryEnum::LEGAL     => 'Legal',
        FileCategoryEnum::PLAN      => 'Plan',
    ],
];
