<?php

namespace Botble\RealEstate\Tables;

use Auth;
use Botble\RealEstate\Repositories\Interfaces\ContractInterface;
use Botble\RealEstate\Models\Contract;
use Botble\Table\Abstracts\TableAbstract;
use Html;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Throwable;
use Yajra\DataTables\DataTables;

class ContractTable extends TableAbstract
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
     * ContractTable constructor.
     * @param DataTables $table
     * @param UrlGenerator $urlDevTool
     * @param ContractInterface $contractRepository
     */
    public function __construct(
        DataTables $table,
        UrlGenerator $urlDevTool,
        ContractInterface $contractRepository
    ) {
        $this->repository = $contractRepository;
        $this->setOption('id', 'table-plugins-contract');
        parent::__construct($table, $urlDevTool);

        if (!Auth::user()->hasAnyPermission(['contract.edit', 'contract.destroy'])) {
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
                if (!Auth::user()->hasPermission('contract.edit')) {
                    return $item->id;
                }
                return Html::link(route('contract.edit', $item->id), $item->id);
            })
            ->editColumn('checkbox', function ($item) {
                return table_checkbox($item->id);
            })
            ->editColumn('company', function ($item) {
                return $item->property && $item->property->company? $item->property->company->name : '';
            })
            ->editColumn('property_id', function ($item) {
                return $item->property? $item->property->id : null;
            })
            ->editColumn('lessee_rut', function ($item) {
                return $item->lessee? $item->lessee->rut : null;
            })
            ->editColumn('start_date', function ($item) {
                return date_from_database($item->start_date, config('core.base.general.date_format.date'));
            })
            ->editColumn('end_date', function ($item) {
                return date_from_database($item->end_date, config('core.base.general.date_format.date'));
            })
            ->editColumn('created_at', function ($item) {
                return date_from_database($item->created_at, config('core.base.general.date_format.date'));
            });

        return apply_filters(BASE_FILTER_GET_LIST_DATA, $data, $this->repository->getModel())
            ->addColumn('operations', function ($item) {
                return table_actions('contract.edit', 'contract.destroy', $item);
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

        $query = $model->with('lessee', 'property');

        return $this->applyScopes(apply_filters(BASE_FILTER_TABLE_QUERY, $query, $model));
    }

    /**
     * @return array
     * @since 2.1
     */
    public function columns()
    {
        return [
            'id'          => [
                'name'  => 're_contracts.id',
                'title' => trans('core/base::tables.id'),
                'class' => 'text-left',
                'width' => '20px',
            ],
            'company'     => [
                'name'  => 're_contracts.id',
                'title' => trans('plugins/real-estate::contract.table.company'),
                'class' => 'text-left',
                'width' => '150px',
            ],
            'property_id' => [
                'name'  => 're_contracts.id',
                'title' => trans('plugins/real-estate::contract.table.property_id'),
                'class' => 'text-center',
            ],
            'lessee_rut'  => [
                'name'  => 're_contracts.id',
                'title' => trans('plugins/real-estate::contract.table.lessee_rut'),
                'class' => 'text-center',
            ],
            'start_date'  => [
                'name'  => 're_contracts.start_date',
                'title' => trans('plugins/real-estate::contract.table.start_date'),
                'class' => 'text-center',
            ],
            'end_date'    => [
                'name'  => 're_contracts.end_date',
                'title' => trans('plugins/real-estate::contract.table.end_date'),
                'class' => 'text-center',
            ],
            'created_at'  => [
                'name'  => 're_contracts.created_at',
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
        $buttons = $this->addCreateButton(route('contract.create'), 'contract.create');

        return apply_filters(BASE_FILTER_TABLE_BUTTONS, $buttons, Contract::class);
    }

    /**
     * @return array
     * @throws Throwable
     */
    public function bulkActions(): array
    {
        return $this->addDeleteAction(route('contract.deletes'), 'contract.destroy', parent::bulkActions());
    }

    /**
     * @return array
     */
    public function getBulkChanges(): array
    {
        return [
            're_contracts.start_date' => [
                'title' => trans('plugins/real-estate::contract.table.start_date'),
                'type'  => 'date',
            ],
            're_contracts.end_date'   => [
                'title' => trans('plugins/real-estate::contract.table.end_date'),
                'type'  => 'date',
            ],
            're_contracts.created_at' => [
                'title' => trans('core/base::tables.created_at'),
                'type'  => 'date',
            ],
        ];
    }
}
