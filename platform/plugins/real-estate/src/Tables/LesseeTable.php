<?php

namespace Botble\RealEstate\Tables;

use Auth;
use Botble\RealEstate\Enums\DefaultStatusEnum;
use Botble\RealEstate\Repositories\Interfaces\LesseeInterface;
use Botble\Table\Abstracts\TableAbstract;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Throwable;
use Yajra\DataTables\DataTables;
use Botble\RealEstate\Models\Lessee;

class LesseeTable extends TableAbstract
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
     * LesseeTable constructor.
     * @param DataTables $table
     * @param UrlGenerator $urlDevTool
     * @param LesseeInterface $lesseeRepository
     */
    public function __construct(
        DataTables $table,
        UrlGenerator $urlDevTool,
        LesseeInterface $lesseeRepository
    ) {
        $this->repository = $lesseeRepository;
        $this->setOption('id', 'table-plugins-lessee');
        parent::__construct($table, $urlDevTool);

        if (!Auth::user()->hasAnyPermission(['lessee.edit', 'lessee.destroy'])) {
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
            ->editColumn('name', function ($item) {
                if (!Auth::user()->hasPermission('lessee.edit')) {
                    return $item->name;
                }
                return anchor_link(route('lessee.edit', $item->id), $item->name);
            })
            ->editColumn('checkbox', function ($item) {
                return table_checkbox($item->id);
            })
            ->editColumn('created_at', function ($item) {
                return date_from_database($item->created_at, config('core.base.general.date_format.date'));
            })
            ->editColumn('status', function ($item) {
                return $item->status->toHtml();
            });

        return apply_filters(BASE_FILTER_GET_LIST_DATA, $data, $this->repository->getModel())
            ->addColumn('operations', function ($item) {
                return table_actions('lessee.edit', 'lessee.destroy', $item);
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
        $query = $model->select([
            're_lessees.id',
            're_lessees.name',
            're_lessees.rut',
            're_lessees.created_at',
            're_lessees.status',
        ]);

        return $this->applyScopes(apply_filters(BASE_FILTER_TABLE_QUERY, $query, $model));
    }

    /**
     * @return array
     * @since 2.1
     */
    public function columns()
    {
        return [
            'id'         => [
                'name'  => 're_lessees.id',
                'title' => trans('core/base::tables.id'),
                'width' => '20px',
                'class' => 'text-center',
            ],
            'name'       => [
                'name'  => 're_lessees.name',
                'title' => trans('core/base::tables.name'),
                'class' => 'text-left',
            ],
            'rut'       => [
                'name'  => 're_lessees.rut',
                'title' => trans('plugins/real-estate::lessee.table.rut'),
                'class' => 'text-center',
            ],
            'created_at' => [
                'name'  => 're_lessees.created_at',
                'title' => trans('core/base::tables.created_at'),
                'width' => '100px',
                'class' => 'text-center',
            ],
            'status'     => [
                'name'  => 're_lessees.status',
                'title' => trans('core/base::tables.status'),
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
        $buttons = $this->addCreateButton(route('lessee.create'), 'lessee.create');

        return apply_filters(BASE_FILTER_TABLE_BUTTONS, $buttons, Lessee::class);
    }

    /**
     * @return array
     * @throws Throwable
     */
    public function bulkActions(): array
    {
        return $this->addDeleteAction(route('lessee.deletes'), 'lessee.destroy', parent::bulkActions());
    }

    /**
     * @return array
     */
    public function getBulkChanges(): array
    {
        return [
            're_lessees.name'       => [
                'title'    => trans('core/base::tables.name'),
                'lessee'     => 'text',
                'validate' => 'required|max:120',
            ],
            're_lessees.status'     => [
                'title'    => trans('core/base::tables.status'),
                'lessee'     => 'select',
                'choices'  => DefaultStatusEnum::labels(),
                'validate' => 'required|in:' . implode(',', DefaultStatusEnum::values()),
            ],
            're_lessees.created_at' => [
                'title' => trans('core/base::tables.created_at'),
                'lessee'  => 'date',
            ],
        ];
    }
}
