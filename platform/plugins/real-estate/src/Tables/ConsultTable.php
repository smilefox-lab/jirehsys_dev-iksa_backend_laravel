<?php

namespace Botble\RealEstate\Tables;

use Auth;
use Botble\RealEstate\Enums\ConsultStatusEnum;
use Botble\RealEstate\Models\Consult;
use Botble\RealEstate\Repositories\Interfaces\ConsultInterface;
use Botble\Table\Abstracts\TableAbstract;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Throwable;
use Yajra\DataTables\DataTables;

class ConsultTable extends TableAbstract
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
     * ConsultTable constructor.
     * @param DataTables $table
     * @param UrlGenerator $urlDevTool
     * @param ConsultInterface $consultRepository
     */
    public function __construct(DataTables $table, UrlGenerator $urlDevTool, ConsultInterface $consultRepository)
    {
        $this->repository = $consultRepository;
        $this->setOption('id', 'table-plugins-consult');
        parent::__construct($table, $urlDevTool);

        if (!Auth::user()->hasAnyPermission(['consult.edit', 'consult.destroy'])) {
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
                if (!Auth::user()->hasPermission('consult.edit')) {
                    return $item->name;
                }
                return anchor_link(route('consult.edit', $item->id), $item->name);
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
                return table_actions('consult.edit', 'consult.destroy', $item);
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
            'consults.id',
            'consults.name',
            'consults.phone',
            'consults.email',
            'consults.created_at',
            'consults.status',
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
                'name'  => 'consults.id',
                'title' => trans('core/base::tables.id'),
                'width' => '20px',
            ],
            'name'       => [
                'name'  => 'consults.name',
                'title' => trans('core/base::tables.name'),
                'class' => 'text-left',
            ],
            'email'      => [
                'name'  => 'consults.email',
                'title' => trans('plugins/real-estate::consult.email.header'),
                'class' => 'text-left',
            ],
            'phone'      => [
                'name'  => 'consults.phone',
                'title' => trans('plugins/real-estate::consult.phone'),
            ],
            'created_at' => [
                'name'  => 'consults.created_at',
                'title' => trans('core/base::tables.created_at'),
                'width' => '100px',
            ],
            'status'     => [
                'name'  => 'consults.status',
                'title' => trans('core/base::tables.status'),
                'width' => '100px',
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
        return apply_filters(BASE_FILTER_TABLE_BUTTONS, [], Consult::class);
    }

    /**
     * @return array
     * @throws Throwable
     */
    public function bulkActions(): array
    {
        return $this->addDeleteAction(route('consult.deletes'), 'consult.destroy', parent::bulkActions());
    }

    /**
     * @return array
     */
    public function getBulkChanges(): array
    {
        return [
            'consults.name'       => [
                'title'    => trans('core/base::tables.name'),
                'type'     => 'text',
                'validate' => 'required|max:120',
            ],
            'consults.status'     => [
                'title'    => trans('core/base::tables.status'),
                'type'     => 'select',
                'choices'  => ConsultStatusEnum::labels(),
                'validate' => 'required|in:' . implode(',', ConsultStatusEnum::values()),
            ],
            'consults.created_at' => [
                'title' => trans('core/base::tables.created_at'),
                'type'  => 'date',
            ],
        ];
    }
}
