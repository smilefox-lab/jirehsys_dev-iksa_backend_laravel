<?php

namespace Botble\Career\Models;

use Botble\Base\Models\BaseModel;
use Botble\Base\Traits\EnumCastable;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Slug\Traits\SlugTrait;

class Career extends BaseModel
{
    use EnumCastable;
    use SlugTrait;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'careers';

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'location',
        'salary',
        'description',
        'status',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'status' => BaseStatusEnum::class,
    ];
}
