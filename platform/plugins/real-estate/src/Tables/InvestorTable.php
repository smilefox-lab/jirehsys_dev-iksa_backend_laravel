<?php

namespace Botble\RealEstate\Tables;

use Auth;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\RealEstate\Repositories\Interfaces\InvestorInterface;
use Botble\Table\Abstracts\TableAbstract;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Throwable;
use Yajra\DataTables\DataTables;
use Botble\RealEstate\Models\Investor;

class InvestorTable extends TableAbstract
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
     * InvestorTable constructor.
     * @param DataTables $table
     * @param UrlGenerator $urlDevTool
     * @param InvestorInterface $investorRepository
     */
    public function __construct(DataTables $table, UrlGenerator $urlDevTool, InvestorInterface $investorRepository)
    {
        $this->repository = $investorRepository;
        $this->setOption('id', 'table-plugins-investor');
        parent::__construct($table, $urlDevTool);

        if (!Auth::user()->hasAnyPermission(['investor.edit', 'investor.destroy'])) {
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
                if (!Auth::user()->hasPermission('investor.edit')) {
                    return $item->name;
                }
                return anchor_link(route('investor.edit', $item->id), $item->name);
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
                return table_actions('investor.edit', 'investor.destroy', $item);
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
            're_investors.id',
            're_investors.name',
            're_investors.created_at',
            're_investors.status',
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
                'name'  => 're_investors.id',
                'title' => trans('core/base::tables.id'),
                'width' => '20px',
            ],
            'name'       => [
                'name'  => 're_investors.name',
                'title' => trans('core/base::tables.name'),
                'class' => 'text-left',
            ],
            'created_at' => [
                'name'  => 're_investors.created_at',
                'title' => trans('core/base::tables.created_at'),
                'width' => '100px',
            ],
            'status'     => [
                'name'  => 're_investors.status',
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
        $buttons = $this->addCreateButton(route('investor.create'), 'investor.create');

        return apply_filters(BASE_FILTER_TABLE_BUTTONS, $buttons, Investor::class);
    }

    /**
     * @return array
     * @throws Throwable
     */
    public function bulkActions(): array
    {
        return $this->addDeleteAction(route('investor.deletes'), 'investor.destroy', parent::bulkActions());
    }

    /**
     * @return array
     */
    public function getBulkChanges(): array
    {
        return [
            're_investors.name'       => [
                'title'    => trans('core/base::tables.name'),
                'type'     => 'text',
                'validate' => 'required|max:120',
            ],
            're_investors.status'     => [
                'title'    => trans('core/base::tables.status'),
                'type'     => 'select',
                'choices'  => BaseStatusEnum::labels(),
                'validate' => 'required|in:' . implode(',', BaseStatusEnum::values()),
            ],
            're_investors.created_at' => [
                'title' => trans('core/base::tables.created_at'),
                'type'  => 'date',
            ],
        ];
    }
}
