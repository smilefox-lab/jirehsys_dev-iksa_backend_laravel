<?php

namespace Botble\ACL\Tables;

use Auth;
use Botble\ACL\Enums\CompanyStatusEnum;
use Botble\ACL\Repositories\Interfaces\CompanyInterface;
use Botble\Table\Abstracts\TableAbstract;
use Illuminate\Contracts\Routing\UrlGenerator;
use Yajra\DataTables\DataTables;

class CompanyTable extends TableAbstract
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
     * CompanyTable constructor.
     * @param DataTables $table
     * @param UrlGenerator $urlDevTool
     * @param CompanyInterface $companyRepository
     */
    public function __construct(
        DataTables $table,
        UrlGenerator $urlDevTool,
        CompanyInterface $companyRepository
    ) {
        $this->repository = $companyRepository;
        $this->setOption('id', 'table-company');
        parent::__construct($table, $urlDevTool);

        if (!Auth::user()->hasAnyPermission(['company.edit', 'company.destroy'])) {
            $this->hasOperations = false;
            $this->hasActions = false;
        }
    }

    /**
     * Display ajax response.
     *
     * @return \Illuminate\Http\JsonResponse
     * @since 2.1
     */
    public function ajax()
    {
        $data = $this->table
            ->eloquent($this->query())
            ->editColumn('name', function ($item) {
                if (!Auth::user()->hasPermission('company.edit')) {
                    return $item->name;
                }
                return anchor_link(route('company.edit', $item->id), $item->name);
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

        return  apply_filters(
                    BASE_FILTER_GET_LIST_DATA,
                    $data,
                    $this->repository->getModel()
                )
                ->addColumn('operations', function ($item) {
                    return table_actions('company.edit', 'company.destroy', $item);
                })
                ->escapeColumns([])
                ->make(true);
    }

    /**
     * Get the query object to be processed by table.
     *
     * @return \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
     * @since 2.1
     */
    public function query()
    {
        $model = $this->repository->getModel();
        $query = $model->select([
            'companies.id',
            'companies.name',
            'companies.created_at',
            'companies.status',
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
            'id' => [
                'name' => 'companies.id',
                'title' => trans('core/base::tables.id'),
                'width' => '20px',
                'class' => 'text-center',
            ],
            'name' => [
                'name' => 'companies.name',
                'title' => trans('core/base::tables.name'),
                'class' => 'text-center',
            ],
            'created_at' => [
                'name' => 'companies.created_at',
                'title' => trans('core/base::tables.created_at'),
                'width' => '100px',
                'class' => 'text-center',
            ],
            'status' => [
                'name' => 'companies.status',
                'title' => trans('core/base::tables.status'),
                'width' => '100px',
                'class' => 'text-center',
            ],
        ];
    }

    /**
     * @return array
     * @since 2.1
     * @throws \Throwable
     */
    public function buttons()
    {
        $buttons = $this->addCreateButton(route('company.create'), 'company.create');

        return apply_filters(BASE_FILTER_TABLE_BUTTONS, $buttons, COMPANY_MODULE_SCREEN_NAME);
    }

    /**
     * @return array
     * @throws \Throwable
     */
    public function bulkActions(): array
    {
        return $this->addDeleteAction(route('company.deletes'), 'company.destroy', parent::bulkActions());
    }

    /**
     * @return array
     */
    public function getBulkChanges(): array
    {
        return [
            'companies.status' => [
                'title'    => trans('core/base::tables.status'),
                'type'     => 'select',
                'choices'  => CompanyStatusEnum::labels(),
                'validate' => 'required|in:' . implode(',', CompanyStatusEnum::values()),
            ],
        ];
    }

    /**
     * @return array
     */
    public function getNames()
    {
        return $this->repository->pluck('companies.name', 'companies.id');
    }
}
