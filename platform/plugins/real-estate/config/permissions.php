<?php

return [
    [
        'name' => 'Real Estates',
        'flag' => 'real_estate.index',
    ],

    [
        'name'        => 'Properties',
        'flag'        => 'property.index',
        'parent_flag' => 'real_estate.index',
    ],
    [
        'name'        => 'Create',
        'flag'        => 'property.create',
        'parent_flag' => 'property.index',
    ],
    [
        'name'        => 'Edit',
        'flag'        => 'property.edit',
        'parent_flag' => 'property.index',
    ],
    [
        'name'        => 'Delete',
        'flag'        => 'property.destroy',
        'parent_flag' => 'property.index',
    ],

    [
        'name'        => 'Property Features',
        'flag'        => 'property_feature.index',
        'parent_flag' => 'real_estate.index',
    ],
    [
        'name'        => 'Create',
        'flag'        => 'property_feature.create',
        'parent_flag' => 'property_feature.index',
    ],
    [
        'name'        => 'Edit',
        'flag'        => 'property_feature.edit',
        'parent_flag' => 'property_feature.index',
    ],
    [
        'name'        => 'Delete',
        'flag'        => 'property_feature.destroy',
        'parent_flag' => 'property_feature.index',
    ],

    [
        'name'        => 'Types',
        'flag'        => 'type.index',
        'parent_flag' => 'real_estate.index',
    ],
    [
        'name'        => 'Create',
        'flag'        => 'type.create',
        'parent_flag' => 'type.index',
    ],
    [
        'name'        => 'Edit',
        'flag'        => 'type.edit',
        'parent_flag' => 'type.index',
    ],
    [
        'name'        => 'Delete',
        'flag'        => 'type.destroy',
        'parent_flag' => 'type.index',
    ],

    [
        'name'        => 'Lessee',
        'flag'        => 'lessee.index',
        'parent_flag' => 'real_estate.index',
    ],
    [
        'name'        => 'Create',
        'flag'        => 'lessee.create',
        'parent_flag' => 'lessee.index',
    ],
    [
        'name'        => 'Edit',
        'flag'        => 'lessee.edit',
        'parent_flag' => 'lessee.index',
    ],
    [
        'name'        => 'Delete',
        'flag'        => 'lessee.destroy',
        'parent_flag' => 'lessee.index',
    ],

    [
        'name'        => 'Contract',
        'flag'        => 'contract.index',
        'parent_flag' => 'real_estate.index',
    ],
    [
        'name'        => 'Create',
        'flag'        => 'contract.create',
        'parent_flag' => 'contract.index',
    ],
    [
        'name'        => 'Edit',
        'flag'        => 'contract.edit',
        'parent_flag' => 'contract.index',
    ],
    [
        'name'        => 'Delete',
        'flag'        => 'contract.destroy',
        'parent_flag' => 'contract.index',
    ],

    [
        'name'        => 'Payment',
        'flag'        => 'payment.index',
        'parent_flag' => 'real_estate.index',
    ],
    [
        'name'        => 'Create',
        'flag'        => 'payment.create',
        'parent_flag' => 'payment.index',
    ],
    [
        'name'        => 'Edit',
        'flag'        => 'payment.edit',
        'parent_flag' => 'payment.index',
    ],
    [
        'name'        => 'Delete',
        'flag'        => 'payment.destroy',
        'parent_flag' => 'payment.index',
    ],

    [
        'name'        => 'Import',
        'flag'        => 'import.index',
        'parent_flag' => 'real_estate.index',
    ],

    [
        'name'        => 'Property API',
        'flag'        => 'api.property',
        'parent_flag' => 'api.index',
    ],
    [
        'name'        => 'Lease',
        'flag'        => 'api.lease',
        'parent_flag' => 'api.index',
    ],
    [
        'name'        => 'Debtor',
        'flag'        => 'api.debtor',
        'parent_flag' => 'api.index',
    ],
    [
        'name'        => 'Type',
        'flag'        => 'api.type',
        'parent_flag' => 'api.index',
    ]
];
