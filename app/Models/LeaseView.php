<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Model;

class LeaseView extends Model
{
    protected $table = 'leases_view';

    public function scopeHolding($query)
    {
        return $query->select(DB::raw('IFNULL(SUM(quota), 0) as expected, IFNULL(SUM(amount), 0) as paid, IFNULL((SUM(quota) - SUM(amount)), 0) as owed'));
    }

    public function scopeByCompany($query)
    {
        return $query->holding()->addSelect('company_id', 'company_name')->groupBy('company_id');
    }

    public function scopeByLineGraph($query)
    {
        return $query
            ->select(DB::raw('MONTH(date) as month, YEAR(date) as year, IFNULL(SUM(quota), 0) as expected, IFNULL(SUM(amount), 0) as paid, IFNULL((SUM(quota) - SUM(amount)), 0) as owed'))
            ->whereNotNull('date')
            ->groupBy(DB::raw('MONTH(date)'), DB::raw('YEAR(date)'));
    }

    public function scopeFilterByCompany($query, $company)
    {
        return $query->where('company_id', $company);
    }

    public function scopeFilterByType($query, $type)
    {
        return $query->where('type_id', $type);
    }

    public function scopeFilterByFind($query, $find)
    {
        return $query->where('property_name', 'like', "%{$find}%")->orWhere('company_name', 'like', "%{$find}%");
    }
}
