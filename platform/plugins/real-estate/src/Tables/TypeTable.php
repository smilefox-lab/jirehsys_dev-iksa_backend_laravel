<?php

namespace Botble\RealEstate\Tables;

use Auth;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\RealEstate\Repositories\Interfaces\TypeInterface;
use Botble\Table\Abstracts\TableAbstract;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Throwable;
use Yajra\DataTables\DataTables;
use Botble\RealEstate\Models\Type;

class TypeTable extends TableAbstract
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
     * TypeTable constructor.
     * @param DataTables $table
     * @param UrlGenerator $urlDevTool
     * @param TypeInterface $typeRepository
     */
    public function __construct(
        DataTables $table,
        UrlGenerator $urlDevTool,
        TypeInterface $typeRepository
    ) {
        $this->repository = $typeRepository;
        $this->setOption('id', 'table-plugins-type');
        parent::__construct($table, $urlDevTool);

        if (!Auth::user()->hasAnyPermission(['type.edit', 'type.destroy'])) {
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
                if (!Auth::user()->hasPermission('type.edit')) {
                    return $item->name;
                }
                return anchor_link(route('type.edit', $item->id), $item->name);
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
                return table_actions('type.edit', 'type.destroy', $item);
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
            're_types.id',
            're_types.name',
            're_types.created_at',
            're_types.status',
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
                'name'  => 're_types.id',
                'title' => trans('core/base::tables.id'),
                'width' => '30px',
                'class' => 'text-center',
            ],
            'name'       => [
                'name'  => 're_types.name',
                'title' => trans('core/base::tables.name'),
                'class' => 'text-left',
            ],
            'status'     => [
                'name'  => 're_types.status',
                'title' => trans('core/base::tables.status'),
                'class' => 'text-center',
            ],
            'created_at' => [
                'name'  => 're_types.created_at',
                'title' => trans('core/base::tables.created_at'),
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
        $buttons = $this->addCreateButton(route('type.create'), 'type.create');

        return apply_filters(BASE_FILTER_TABLE_BUTTONS, $buttons, Type::class);
    }

    /**
     * @return array
     * @throws Throwable
     */
    public function bulkActions(): array
    {
        return $this->addDeleteAction(route('type.deletes'), 'type.destroy', parent::bulkActions());
    }

    /**
     * @return array
     */
    public function getBulkChanges(): array
    {
        return [
            're_types.name'       => [
                'title'    => trans('core/base::tables.name'),
                'type'     => 'text',
                'validate' => 'required|max:120',
            ],
            're_types.status'     => [
                'title'    => trans('core/base::tables.status'),
                'type'     => 'select',
                'choices'  => BaseStatusEnum::labels(),
                'validate' => 'required|in:' . implode(',', BaseStatusEnum::values()),
            ],
            're_types.created_at' => [
                'title' => trans('core/base::tables.created_at'),
                'type'  => 'date',
            ],
        ];
    }
}
