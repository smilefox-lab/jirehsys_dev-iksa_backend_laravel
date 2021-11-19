<?php

namespace Botble\RealEstate\Models;

use Botble\Base\Models\BaseModel;
use Botble\Base\Traits\EnumCastable;
use Botble\RealEstate\Enums\DefaultStatusEnum;

class Type extends BaseModel
{
    use EnumCastable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 're_types';

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'status',
        'order',
        'is_default',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'status' => DefaultStatusEnum::class,
    ];
}
