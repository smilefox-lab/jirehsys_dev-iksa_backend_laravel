<?php

namespace Botble\RealEstate\Tables;

use Auth;
use Botble\RealEstate\Repositories\Interfaces\PaymentInterface;
use Botble\RealEstate\Models\Payment;
use Botble\Table\Abstracts\TableAbstract;
use Html;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Throwable;
use Yajra\DataTables\DataTables;

class PaymentTable extends TableAbstract
{

    /**
     * @var bool
     */
    protected $hasActions = true;

    /**
     * @var bool
     */
    protected $hasFilter = true;

    /**
     * PaymentTable constructor.
     * @param DataTables $table
     * @param UrlGenerator $urlDevTool
     * @param PaymentInterface $paymentRepository
     */
    public function __construct(
        DataTables $table,
        UrlGenerator $urlDevTool,
        PaymentInterface $paymentRepository
    ) {
        $this->repository = $paymentRepository;
        $this->setOption('id', 'table-plugins-payment');
        parent::__construct($table, $urlDevTool);

        if (!Auth::user()->hasAnyPermission(['payment.edit', 'payment.destroy'])) {
            $this->hasOperations = false;
            $this->hasActions = false;
        }
    }

    /**
     * Display ajax response.
     *
     * @return JsonResponse
     * @since 2.1
     */
    public function ajax()
    {
        $data = $this->table
            ->eloquent($this->query())
            ->editColumn('id', function ($item) {
                if (!Auth::user()->hasPermission('payment.edit')) {
                    return $item->id;
                }
                return Html::link(route('payment.edit', $item->id), $item->id);
            })
            ->editColumn('checkbox', function ($item) {
                return table_checkbox($item->id);
            })
            ->editColumn('property', function ($item) {
                return $item->contract && $item->contract->property? $item->contract->property->name : '';
            })
            ->editColumn('contract_id', function ($item) {
                return $item->contract? $item->contract->name : '';
            })
            ->editColumn('date', function ($item) {
                return date_from_database($item->date, config('core.base.general.date_format.date'));
            })
            ->editColumn('created_at', function ($item) {
                return date_from_database($item->created_at, config('core.base.general.date_format.date'));
            });

        return apply_filters(BASE_FILTER_GET_LIST_DATA, $data, $this->repository->getModel())
            ->addColumn('operations', function ($item) {
                return table_actions('payment.edit', 'payment.destroy', $item);
            })
            ->escapeColumns([])
            ->make(true);
    }

    /**
     * Get the query object to be processed by table.
     *
     * @return \Illuminate\Database\Query\Builder|Builder
     * @since 2.1
     */
    public function query()
    {
        $model = $this->repository->getModel();

        $query = $model->with('contract');

        return $this->applyScopes(apply_filters(BASE_FILTER_TABLE_QUERY, $query, $model));
    }

    /**
     * @return array
     * @since 2.1
     */
    public function columns()
    {
        return [
            'id'               => [
                'name'  => 're_payments.id',
                'title' => trans('core/base::tables.id'),
                'width' => '20px',
                'class' => 'text-center',
            ],
            'property'       => [
                'name'  => 're_payments.contract_id',
                'title' => trans('plugins/real-estate::payment.table.property_id'),
                'class' => 'text-center',
            ],
            'contract_id'       => [
                'name'  => 're_payments.contract_id',
                'title' => trans('plugins/real-estate::payment.table.contract_id'),
                'class' => 'text-center',
            ],
            'date'         => [
                'name'  => 're_payments.date',
                'title' => trans('plugins/real-estate::payment.table.date'),
                'class' => 'text-center',
            ],
            'amount'         => [
                'name'  => 're_payments.amount',
                'title' => trans('plugins/real-estate::payment.table.amount'),
                'class' => 'text-center',
            ],
            'created_at'       => [
                'name'  => 're_payments.created_at',
                'title' => trans('core/base::tables.created_at'),
                'width' => '100px',
                'class' => 'text-center',
            ],
        ];
    }

    /**
     * @return array
     * @throws Throwable
     * @since 2.1
     */
    public function buttons()
    {
        $buttons = $this->addCreateButton(route('payment.create'), 'payment.create');

        return apply_filters(BASE_FILTER_TABLE_BUTTONS, $buttons, Payment::class);
    }

    /**
     * @return array
     * @throws Throwable
     */
    public function bulkActions(): array
    {
        return $this->addDeleteAction(route('payment.deletes'), 'payment.destroy', parent::bulkActions());
    }

    /**
     * @return array
     */
    public function getBulkChanges(): array
    {
        return [
            're_payments.date'  => [
                'title' => trans('plugins/real-estate::payment.table.date'),
                'type'  => 'date',
            ],
            're_payments.created_at'  => [
                'title' => trans('core/base::tables.created_at'),
                'type'  => 'date',
            ],
        ];
    }
}
