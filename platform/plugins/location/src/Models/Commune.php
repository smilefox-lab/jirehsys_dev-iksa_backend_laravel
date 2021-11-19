<?php

namespace Botble\Location\Models;

use Botble\Base\Traits\EnumCastable;
use Botble\Base\Models\BaseModel;
use Botble\Location\Enums\DefaultStatusEnum;

class Commune extends BaseModel
{
    use EnumCastable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'communes';

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'region_id',
        'order',
        'is_default',
        'status',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'status' => DefaultStatusEnum::class,
    ];

    /**
     * @return BelongsTo
     */
    public function region()
    {
        return $this->belongsTo(Region::class)->withDefault();
    }
}
