<?php

namespace Botble\RealEstate\Models;

use Botble\RealEstate\Enums\ConsultStatusEnum;
use Botble\Base\Traits\EnumCastable;
use Botble\Base\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Consult extends BaseModel
{
    use EnumCastable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'consults';

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'content',
        'project_id',
        'property_id',
        'status',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'status' => ConsultStatusEnum::class,
    ];

    /**
     * @return BelongsTo
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * @return BelongsTo
     */
    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }
}
