<?php

namespace Botble\ACL\Models;

use Botble\ACL\Enums\CompanyStatusEnum;
use User;
use Botble\Base\Traits\EnumCastable;
use Botble\Base\Models\BaseModel;
use Exception;

class Company extends BaseModel
{
    use EnumCastable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'companies';

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'rut',
        'address',
        'phone',
        'files',
        'status',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'status' => CompanyStatusEnum::class,
    ];

    /**
     * @param string $value
     * @return array
     */
    public function getFilesAttribute($value)
    {
        try {
            if ($value === '[null]') {
                return [];
            }

            return json_decode($value) ?: [];
        } catch (Exception $exception) {
            return [];
        }
    }

    /**
     * @return hasMany
     */
    public function users()
    {
        return $this->hasMany(User::class, 'company_id');
    }
}
