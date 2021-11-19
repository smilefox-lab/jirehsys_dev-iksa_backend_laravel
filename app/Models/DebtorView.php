<?php

namespace App\Models;

use Auth;
use Botble\RealEstate\Models\Payment;
use Carbon\Carbon;
use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class DebtorView extends Model
{
    protected $table = 'debtors_view';

    public static function scopeOverview($query, $company = null, $date = '')
    {
        $date = empty($date) ? Carbon::now() : Carbon::createFromFormat('d/m/Y', $date);

        $maxDate = $query->selectRaw('MAX(payment_date)')->whereRaw("payment_date <= '{$date}'")->toSql();

        $latest = self::selectRaw("DATE_FORMAT(payment_date, '%Y-%m') payment_date, COALESCE(TRUNCATE(SUM(CASE WHEN status = 'Pagado' THEN paid ELSE 0 END), 0), 0) total_paid, COALESCE(TRUNCATE(SUM(CASE WHEN status = 'Mora' OR status = 'Retraso' THEN expected ELSE 0 END), 0), 0) AS total_owed, COALESCE(TRUNCATE(SUM(CASE WHEN status = 'Pagado' THEN 1 ELSE 0 END), 0), 0) AS property_paid, COALESCE(TRUNCATE(SUM(CASE WHEN status = 'Mora' OR status = 'Retraso' THEN 1 ELSE 0 END), 0), 0) AS debtors, CASE WHEN ({$maxDate}) <= DATE(payment_date) THEN 'latest' ELSE 'before' END AS status")->filterByDateLatest($date->format('Y-m-d'))->groupBy('payment_date')->orderBy('payment_date', 'desc')->limit(2);

        if (!is_null($company)) {
            $latest = $latest->filterByCompany(Auth::user()->company->id ?? $company);
        }

        $query = $latest;

        return $query;
    }

    public function scopeStatus($query, $status = [], $operator = 'and')
    {
        if ($operator == 'and') {
            foreach ($status as $key => $value) {
               $query = $query->where('status', '=', $value);
            }
        } else {
            foreach ($status as $key => $value) {
                $query = $query->orWhere('status', '=', $value);
            }
        }
        return $query;
    }

    public function scopeTop($query)
    {
        return $query->select('property_id', 'status', 'months', 'expected', 'lessee_name')
            ->status(['Mora', 'Retraso'], 'or')
            ->orderBy('months', 'desc')
            ->take(10);
    }

    public function scopeSelectByLessee($query)
    {
        return $query->select('property_id', 'lessee_name', 'lessee_rut', 'status', 'months', 'expected');
    }

    public function scopeByProperty($query)
    {
        return $query->select('property_id', 'property_name', 'lessee_contact_name', 'lessee_name', 'appraisal', 'paid', 'expected', 'status', 'months', 'payment_date');
    }

    public function scopeFilterByCompany($query, $company)
    {
        return $query->where('company_id', $company);
    }

    public function scopeFilterToFindLeases($query, $find)
    {
        return $query
                ->where('property_name', 'like', "%{$find}%")
                ->orWhere('lessee_name', 'like', "%{$find}%")
                ->orWhere('property_id', 'like', "%{$find}%")
                ->orWhere('company_name', 'like', "%{$find}%");
    }

    public function scopeFilterToFindDebtors($query, $find)
    {
        return $query
                ->where('lessee_name', 'like', "%{$find}%")
                ->orWhere('lessee_rut', 'like', "%{$find}%")
                ->orWhere('property_id', 'like', "%{$find}%");
    }

    public function scopeFilterByType($query, $type)
    {
        return $query->where('property_type', $type);
    }

    public function scopeFilterByDateLatest($query, $date = '')
    {
        $date = empty($date) ? Carbon::now() : Carbon::parse($date);

        return $query->where('payment_date', '<=', $date->format('Y-m-d'));
    }

    public function scopeFilterByDateBefore($query, $date = '')
    {
        $date = empty($date) ? Carbon::now() : Carbon::parse($date);
        return $query->where('payment_date', '<=', $date->subMonth()->format('Y-m-d'));
    }

    public function scopeLeaseIndicators($query, $company = null, $date = '')
    {
        $date = empty($date) ? Carbon::now() : Carbon::createFromFormat('d/m/Y', $date);

        $maxDate = $query->selectRaw('MAX(payment_date)')->whereRaw("payment_date <= '{$date}'")->toSql();

        $latest = $this->selectRaw("DATE_FORMAT(payment_date, '%Y-%m') payment_date, CASE WHEN ({$maxDate}) <= DATE(payment_date) THEN 'latest' ELSE 'before' END AS status, COALESCE(ROUND(SUM(expected), 0), 0) as expected, COALESCE(ROUND(SUM(paid), 0), 0) as paid, COALESCE(ROUND(SUM(expected - paid),0),0) as owed")->filterByDateLatest($date->format('Y-m-d'))->where('status', 'Pagado')->groupBy(DB::raw('payment_date'))->orderBy('payment_date', 'desc')->limit(2);

        if (!is_null($company)) {
            $latest = $latest->filterByCompany(Auth::user()->company->id ?? $company);
        }

        $query = $latest;

        return $query;
    }

    public function scopeLeaseCompaniesIndicators($query, $date = '')
    {
        $date = empty($date) ? Carbon::now() : Carbon::createFromFormat('d/m/Y', $date);

        $maxDate = $query->selectRaw('MAX(payment_date)')->whereRaw("payment_date <= '{$date}'")->toSql();

        $latest = $this->selectRaw("company_id, company_name, DATE_FORMAT(payment_date, '%Y-%m') payment_date, CASE WHEN ({$maxDate}) <= DATE(payment_date) THEN 'latest' ELSE 'before' END AS status, COALESCE(ROUND(SUM(expected), 0), 0) as expected, COALESCE(ROUND(SUM(paid), 0), 0) as paid, COALESCE(ROUND(SUM(expected - paid),0),0) as owed")->filterByDateLatest($date->format('Y-m-d'))->where('status', 'Pagado')->groupBy(DB::raw('company_id, company_name, payment_date'))->having('status', 'latest')->orderBy('company_id')->orderBy('payment_date', 'desc');

        $query = $latest;

        return $query;
    }

    public function scopeHistoryGraphDefaultAndDelay($query)
    {
        return $query->selectRaw("DATE_FORMAT (payment_date, '%m-%Y') as point_date,
        SUM(CASE WHEN status = 'Retraso' THEN expected ELSE 0 END) as delay,
        SUM(CASE WHEN status = 'Mora' THEN expected ELSE 0 END) as `default`")->where("status", "Mora")->orWhere("status", "Retraso")->groupBy('point_date');
    }
}
