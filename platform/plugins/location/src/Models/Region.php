<?php

namespace Botble\Location\Models;

use Botble\Base\Traits\EnumCastable;
use Botble\Base\Models\BaseModel;
use Botble\Location\Enums\DefaultStatusEnum;

class Region extends BaseModel
{
    use EnumCastable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'regions';

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'country_id',
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
    public function country()
    {
        return $this->belongsTo(Country::class)->withDefault();
    }

    protected static function boot()
    {
        parent::boot();
        static::deleting(function (Region $region) {
            Commune::where('region_id', $region->id)->delete();
        });
    }
}
