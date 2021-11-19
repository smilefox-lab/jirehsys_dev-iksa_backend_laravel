<?php

namespace Botble\RealEstate\Models;

use Botble\ACL\Models\Company;
use Botble\Base\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\Pivot;

class Contract extends Pivot
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 're_contracts';

    /**
     * @var array
     */
    protected $fillable = [
        'property_id',
        'lessee_id',
        'start_date',
        'end_date',
        'cutoff_date',
        'name',
        'quota',
        'contribution',
        'contribution_quota',
        'income',
    ];

    public $incrementing = true;

    /**
     * @return belongsTo
     */
    public function property()
    {
        return $this->belongsTo(Property::class, 'property_id');
    }

    /**
     * @return belongsTo
     */
    public function lessee()
    {
        return $this->belongsTo(Lessee::class, 'lessee_id');
    }

    public function scopeAlert($query)
    {
        return $query->whereRaw("(TIMESTAMPDIFF (DAY, CURRENT_DATE, end_date)) > 0")->whereRaw("(TIMESTAMPDIFF(DAY, CURRENT_DATE, end_date)) <= 16");
    }
}
