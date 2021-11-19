<?php

use Botble\Base\Enums\BaseStatusEnum;

return [
    'statuses' => [
        BaseStatusEnum::DRAFT     => 'Borrador',
        BaseStatusEnum::PENDING   => 'Pendiente',
        BaseStatusEnum::PUBLISHED => 'Publicado',
    ],
];
