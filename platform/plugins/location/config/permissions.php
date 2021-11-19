<?php

return [
    [
        'name'     => 'Location',
        'flag'     => 'location.index'
    ],

    [
        'name'        => 'Countries',
        'flag'        => 'country.index',
        'parent_flag' => 'location.index',
    ],
    [
        'name'        => 'Create',
        'flag'        => 'country.create',
        'parent_flag' => 'country.index',
    ],
    [
        'name'        => 'Edit',
        'flag'        => 'country.edit',
        'parent_flag' => 'country.index',
    ],
    [
        'name'        => 'Delete',
        'flag'        => 'country.destroy',
        'parent_flag' => 'country.index',
    ],

    [
        'name'        => 'States',
        'flag'        => 'state.index',
        'parent_flag' => 'location.index',
    ],
    [
        'name'        => 'Create',
        'flag'        => 'state.create',
        'parent_flag' => 'state.index',
    ],
    [
        'name'        => 'Edit',
        'flag'        => 'state.edit',
        'parent_flag' => 'state.index',
    ],
    [
        'name'        => 'Delete',
        'flag'        => 'state.destroy',
        'parent_flag' => 'state.index',
    ],

    [
        'name' => 'Cities',
        'flag' => 'city.index',
        'parent_flag' => 'location.index',
    ],
    [
        'name'        => 'Create',
        'flag'        => 'city.create',
        'parent_flag' => 'city.index',
    ],
    [
        'name'        => 'Edit',
        'flag'        => 'city.edit',
        'parent_flag' => 'city.index',
    ],
    [
        'name'        => 'Delete',
        'flag'        => 'city.destroy',
        'parent_flag' => 'city.index',
    ],


    [
        'name'        => 'Region',
        'flag'        => 'region.index',
        'parent_flag' => 'location.index',
    ],
    [
        'name'        => 'Create',
        'flag'        => 'region.create',
        'parent_flag' => 'region.index',
    ],
    [
        'name'        => 'Edit',
        'flag'        => 'region.edit',
        'parent_flag' => 'region.index',
    ],
    [
        'name'        => 'Delete',
        'flag'        => 'region.destroy',
        'parent_flag' => 'region.index',
    ],

    [
        'name'        => 'Commune',
        'flag'        => 'commune.index',
        'parent_flag' => 'location.index',
    ],
    [
        'name'        => 'Create',
        'flag'        => 'commune.create',
        'parent_flag' => 'commune.index',
    ],
    [
        'name'        => 'Edit',
        'flag'        => 'commune.edit',
        'parent_flag' => 'commune.index',
    ],
    [
        'name'        => 'Delete',
        'flag'        => 'comuna.destroy',
        'parent_flag' => 'comuna.index',
    ],
];
