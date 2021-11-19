<?php

namespace Botble\Location\Models;

use Botble\Base\Traits\EnumCastable;
use Botble\Base\Models\BaseModel;
use Botble\Location\Enums\DefaultStatusEnum;

class Country extends BaseModel
{
    use EnumCastable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'countries';

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'nationality',
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

    protected static function boot()
    {
        parent::boot();
        static::deleting(function (Country $country) {
            $states = State::get();
            foreach ($states as $state) {
                State::where('id', $state->id)->delete();
            }

            City::where('country_id', $country->id)->delete();
        });
    }
}
