<?php

namespace Botble\RealEstate\Models;

use Botble\Base\Models\BaseModel;

class Payment extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 're_payments';

    /**
     * @var array
     */
    protected $fillable = [
        'contract_id',
        'date',
        'amount',
    ];

    /**
     * @return BelongsTo
     */
    public function contract()
    {
        return $this->belongsTo(Contract::class, 'contract_id')->withDefault();
    }
}
