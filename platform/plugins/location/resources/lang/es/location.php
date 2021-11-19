<?php

use Botble\Location\Enums\DefaultStatusEnum;

return [
    'name'       => 'Ubicaciones',
    'create'     => 'Nueva ubicación',
    'edit'       => 'Editar ubicación',
    'all_states' => 'Todo los estados',

    'statuses' => [
        DefaultStatusEnum::ENABLED  => 'Habilitado',
        DefaultStatusEnum::DISABLED => 'Deshabilitado',
    ],
];
