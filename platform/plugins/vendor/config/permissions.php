<?php

return [
    [
        'name' => 'Vendors',
        'flag' => 'vendor.index',
    ],
    [
        'name'        => 'Create',
        'flag'        => 'vendor.create',
        'parent_flag' => 'vendor.index',
    ],
    [
        'name'        => 'Edit',
        'flag'        => 'vendor.edit',
        'parent_flag' => 'vendor.index',
    ],
    [
        'name'        => 'Delete',
        'flag'        => 'vendor.destroy',
        'parent_flag' => 'vendor.index',
    ],

    [
        'name' => 'Packages',
        'flag' => 'package.index',
    ],
    [
        'name'        => 'Create',
        'flag'        => 'package.create',
        'parent_flag' => 'package.index',
    ],
    [
        'name'        => 'Edit',
        'flag'        => 'package.edit',
        'parent_flag' => 'package.index',
    ],
    [
        'name'        => 'Delete',
        'flag'        => 'package.destroy',
        'parent_flag' => 'package.index',
    ],

];
