<?php

namespace Botble\Vendor\Tables;

use Botble\Vendor\Models\Vendor;
use Illuminate\Support\Facades\Auth;
use Botble\Vendor\Repositories\Interfaces\VendorInterface;
use Botble\Table\Abstracts\TableAbstract;
use Illuminate\Contracts\Routing\UrlGenerator;
use Yajra\DataTables\DataTables;

class VendorTable extends TableAbstract
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
     * VendorTable constructor.
     * @param DataTables $table
     * @param UrlGenerator $urlGenerator
     * @param VendorInterface $vendorRepository
     */
    public function __construct(DataTables $table, UrlGenerator $urlGenerator, VendorInterface $vendorRepository)
    {
        $this->repository = $vendorRepository;
        $this->setOption('id', 'table-vendors');
        parent::__construct($table, $urlGenerator);

        if (!Auth::user()->hasAnyPermission(['vendor.edit', 'vendor.destroy'])) {
            $this->hasOperations = false;
            $this->hasActions = false;
        }
    }

    /**
     * Display ajax response.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @since 2.1
     */
    public function ajax()
    {
        $data = $this->table
            ->eloquent($this->query())
            ->editColumn('first_name', function ($item) {
                if (!Auth::user()->hasPermission('vendor.edit')) {
                    return $item->getFullName();
                }

                return anchor_link(route('vendor.edit', $item->id), $item->getFullName());
            })
            ->editColumn('checkbox', function ($item) {
                return table_checkbox($item->id);
            })
            ->editColumn('created_at', function ($item) {
                return date_from_database($item->created_at, config('core.base.general.date_format.date'));
            });

        return apply_filters(BASE_FILTER_GET_LIST_DATA, $data, $this->repository->getModel())
            ->addColumn('operations', function ($item) {
                return table_actions('vendor.edit', 'vendor.destroy', $item);
            })
            ->escapeColumns([])
            ->make(true);
    }

    /**
     * Get the query object to be processed by the table.
     *
     * @return \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
     *
     * @since 2.1
     */
    public function query()
    {
        $model = app(VendorInterface::class)->getModel();
        $query = $model
            ->select([
                'vendors.id',
                'vendors.first_name',
                'vendors.last_name',
                'vendors.email',
                'vendors.created_at',
            ]);
        return $this->applyScopes(apply_filters(BASE_FILTER_TABLE_QUERY, $query, $model));
    }

    /**
     * @return array
     *
     * @since 2.1
     */
    public function columns()
    {
        return [
            'id'         => [
                'name'  => 'vendors.id',
                'title' => trans('core/base::tables.id'),
                'width' => '20px',
            ],
            'first_name' => [
                'name'  => 'vendors.first_name',
                'title' => trans('core/base::tables.name'),
                'class' => 'text-left',
            ],
            'email'      => [
                'name'  => 'vendors.email',
                'title' => trans('core/base::tables.email'),
                'class' => 'text-left',
            ],
            'created_at' => [
                'name'  => 'vendors.created_at',
                'title' => trans('core/base::tables.created_at'),
                'width' => '100px',
            ],
        ];
    }

    /**
     * @return array
     *
     * @since 2.1
     * @throws \Throwable
     */
    public function buttons()
    {
        $buttons = $this->addCreateButton(route('vendor.create'), 'vendor.create');

        return apply_filters(BASE_FILTER_TABLE_BUTTONS, $buttons, Vendor::class);
    }

    /**
     * @return array
     * @throws \Throwable
     */
    public function bulkActions(): array
    {
        return $this->addDeleteAction(route('vendor.deletes'), 'vendor.destroy', parent::bulkActions());
    }

    /**
     * @return array
     */
    public function getBulkChanges(): array
    {
        return [
            'vendors.first_name' => [
                'title'    => __('First name'),
                'type'     => 'text',
                'validate' => 'required|max:120',
            ],
            'vendors.last_name' => [
                'title'    => __('Last name'),
                'type'     => 'text',
                'validate' => 'required|max:120',
            ],
            'vendors.email'      => [
                'title'    => trans('core/base::tables.email'),
                'type'     => 'text',
                'validate' => 'required|max:120|email',
            ],
            'vendors.created_at' => [
                'title' => trans('core/base::tables.created_at'),
                'type'  => 'date',
            ],
        ];
    }
}
