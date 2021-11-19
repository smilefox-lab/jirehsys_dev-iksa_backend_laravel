<?php

namespace Botble\RealEstate\Models;

use Botble\Base\Models\BaseModel;
use Botble\Base\Traits\EnumCastable;
use Botble\RealEstate\Enums\DefaultStatusEnum;

class Lessee extends BaseModel
{
    use EnumCastable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 're_lessees';

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'rut',
        'email',
        'phone',
        'type',
        'status',
        'contact_name'
    ];

    /**
     * @var array
     */
    protected $casts = [
        'status' => DefaultStatusEnum::class,
    ];

    /**
     * @return hasMany
     */
    public function contracts()
    {
        return $this->hasMany(Contract::class);
    }
}
