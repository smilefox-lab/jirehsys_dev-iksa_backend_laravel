<?php

namespace Botble\RealEstate\Models;

use Botble\ACL\Models\Company;
use Botble\ACL\Models\User;
use Botble\Base\Models\BaseModel;
use Botble\Base\Supports\Avatar;
use Botble\Base\Traits\EnumCastable;
use Botble\Location\Models\Commune;
use Botble\RealEstate\Enums\PropertyStatusEnum;
use Botble\Slug\Traits\SlugTrait;
use Carbon\Carbon;
use DB;
use Exception;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Arr;
use RvMedia;

class Property extends BaseModel
{
    use SlugTrait;
    use EnumCastable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 're_properties';

    /**
     * @var array
     */
    protected $fillable = [
        'id',
        'name',
        'description',
        'location',
        'images',
        'square',
        'square_build',
        'price',
        'status',
        'commune_id',
        'company_id',
        'type_id',
        'role',
        'leaves',
        'number',
        'year',
        'buy',
        'date_deed',
        'appraisal',
        'pesos',
        'uf',
        'coordinates',
        'profitability',
        'files_technical',
        'files_legal',
        'files_plane'
    ];

    /**
     * @var array
     */
    protected $casts = [
        'status' => PropertyStatusEnum::class,
    ];

    /**
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'expire_date',
    ];

    /**
     * @return BelongsToMany
     */
    public function features(): BelongsToMany
    {
        return $this->belongsToMany(Feature::class, 're_property_features', 'property_id', 'feature_id');
    }

    /**
     * @param string $value
     * @return array
     */
    public function getImagesAttribute($value)
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
     * @param string $value
     * @return array
     */
    public function getFilesTechnicalAttribute($value)
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
     * @param string $value
     * @return array
     */
    public function getFilesLegalAttribute($value)
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
     * @param string $value
     * @return array
     */
    public function getFilesPlaneAttribute($value)
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
     * @return string|null
     */
    public function getImageAttribute(): ?string
    {
        return Arr::first($this->images) ?? null;
    }

    /**
     * @return UrlGenerator|string
     */
    public function getImageUrlAttribute()
    {
        return Arr::first($this->images) ? RvMedia::url(Arr::first($this->images)) : null;
    }

    /**
     * @return UrlGenerator|string
     */
    public function getImagesUrlAttribute()
    {
        $images = [];
        foreach ($this->images as $image) {
            array_push($images, RvMedia::url($image));
        }
        return $images;
    }

    /**
     * @return BelongsTo
     */
    public function commune(): BelongsTo
    {
        return $this->belongsTo(Commune::class)->withDefault();
    }

    /**
     * @return MorphTo
     */
    public function author(): MorphTo
    {
        if (!is_plugin_active('vendor')) {
            return $this->morphTo(null, User::class)->withDefault();
        }

        return $this->morphTo()->withDefault();
    }

    /**
     * @return BelongsTo
     */
    public function type(): BelongsTo
    {
        return $this->belongsTo(Type::class, 'type_id')->withDefault();
    }

    /**
     * @return BelongsTo
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class)->withDefault();
    }

    /**
     * @return hasManyThrough
     */
    public function payments()
    {
        return $this->hasManyThrough(
            Payment::class,
            Contract::class,
            'property_id',
            'contract_id',
            'id',
            'id'
        );
    }

    /**
     * @return belongsToMany
     */
    public function lessees()
    {
        return $this->belongsToMany(Lessee::class, 're_contracts')->using(Contract::class);
    }

    /**
     * @return hasMany
     */
    public function contracts()
    {
        return $this->hasMany(Contract::class);
    }

    public static function scopeAliasTable()
    {
        return self::from('re_properties', 'p');
    }

    public function scopeTypeStatus($query)
    {
        return $query->leftJoin('re_types as t', 't.id', '=', 'p.type_id')
                     ->selectRaw('t.name as name, COUNT(p.id) as quantity, SUM(p.square) as square, p.status');
    }

    public function scopeRawJoinCommune($query)
    {
        return $query->leftJoin('communes as cm', 'p.commune_id', '=', 'cm.id');
    }

    public function scopeFilterByCompany($query, $company)
    {
        return $query->where('company_id', $company);
    }

    public function scopeFilterToFind($query, $find)
    {
        $query->getModel()->setTable('p');
        return $query
                ->whereHas('commune', function ($query) use ($find){
                    $query->where('communes.name', 'like', "%{$find}%");
                })
                ->orWhere(function($query) use ($find){
                    $query->orWhere('p.id', 'like', "%{$find}%");
                    $query->orWhere('p.name', 'like', "%{$find}%");
                    $query->orWhere('location', 'like', "%{$find}%");
                    $query->orWhere('role', 'like', "%{$find}%");
                });
    }

    public function scopeFilterByType($query, $type)
    {
        return $query->where('type_id', $type);
    }

    public function scopeFilterByDate($query, $date)
    {
        $date = Carbon::createFromFormat('d/m/Y', $date)->format('Y-m-d');
        return $query->where(function($query) use ($date) {
            $query->where('start_date', $date);
            $query->orWhere('cutoff_date', $date);
        });
    }

    public function scopeWithContractsJoin($query)
    {
        return $this::from('re_properties', 'p')
                    ->rightJoin('companies as c', 'p.company_id', '=', 'c.id')
                    ->rightJoin('communes as cm', 'p.commune_id', '=', 'cm.id')
                    ->rightJoin('re_contracts as rec', 'p.id', '=', 'rec.property_id')
                    ->rightJoin('re_lessees as rel', 'rec.lessee_id', '=', 'rel.id')
                    ->rightJoin('re_payments as rep', 'rec.id', '=', 'rep.contract_id')
                    ->selectRaw('c.id company_id, c.name company_name, p.id, p.name property_name, p.appraisal, p.type_id, p.status, p.commune_id, p.role, p.square, rec.start_date, rec.end_date, rec.cutoff_date, rel.name lessee_name, rel.contact_name lessee_contact_name, cm.name commune_name,
                    SUM(CASE WHEN month(rep.date) = 1 THEN rep.amount ELSE 0 END) enero,
                    SUM(CASE WHEN month(rep.date) = 2 THEN rep.amount ELSE 0 END) febrero,
                    SUM(CASE WHEN month(rep.date) = 3 THEN rep.amount ELSE 0 END) marzo,
                    SUM(CASE WHEN month(rep.date) = 4 THEN rep.amount ELSE 0 END) abril,
                    SUM(CASE WHEN month(rep.date) = 5 THEN rep.amount ELSE 0 END) mayo,
                    SUM(CASE WHEN month(rep.date) = 6 THEN rep.amount ELSE 0 END) junio,
                    SUM(CASE WHEN month(rep.date) = 7 THEN rep.amount ELSE 0 END) julio,
                    SUM(CASE WHEN month(rep.date) = 8 THEN rep.amount ELSE 0 END) agosto,
                    SUM(CASE WHEN month(rep.date) = 9 THEN rep.amount ELSE 0 END) septiembre,
                    SUM(CASE WHEN month(rep.date) = 10 THEN rep.amount ELSE 0 END) octubre,
                    SUM(CASE WHEN month(rep.date) = 11 THEN rep.amount ELSE 0 END) noviembre,
                    SUM(CASE WHEN month(rep.date) = 12 THEN rep.amount ELSE 0 END) diciembre')
                    ->groupBy('p.id', 'rel.id', 'rec.id');
    }

    public function scopeProfitability($query)
    {
        return $query->sum('profitability');
    }
}
