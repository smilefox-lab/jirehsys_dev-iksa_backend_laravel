<?php

namespace Botble\RealEstate\Tables;

use Auth;
use Botble\ACL\Repositories\Interfaces\CompanyInterface;
use Botble\RealEstate\Enums\PropertyStatusEnum;
use Botble\RealEstate\Models\Property;
use Botble\RealEstate\Repositories\Interfaces\PropertyInterface;
use Botble\Table\Abstracts\TableAbstract;
use Html;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;
use Throwable;
use Yajra\DataTables\DataTables;

class PropertyTable extends TableAbstract
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
     * @var CompanyInterface
     */
    protected $companyRepository;
    /**
     * TagTable constructor.
     * @param DataTables $table
     * @param UrlGenerator $urlGenerator
     * @param PropertyInterface $propertyRepository
     */
    public function __construct(
        DataTables $table,
        UrlGenerator $urlGenerator,
        PropertyInterface $propertyRepository,
        CompanyInterface $companyRepository
    ) {
        $this->repository = $propertyRepository;
        $this->companyRepository = $companyRepository;
        $this->setOption('id', 'table-plugins-real-estate-property');
        parent::__construct($table, $urlGenerator);
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
                if (!Auth::user()->hasPermission('users.edit')) {
                    return $item->name;
                }

                return Html::link(route('property.edit', $item->id), $item->name);
            })
            ->editColumn('checkbox', function ($item) {
                return table_checkbox($item->id);
            })
            ->editColumn('company_id', function ($item) {
                return $item->company->name ?? trans('core/acl::company.no_company_assigned');
            })
            ->editColumn('date_deed', function ($item) {
                return date_from_database($item->date_deed, config('core.base.general.date_format.date'));
            })
            ->editColumn('status', function ($item) {
                return $item->status->toHtml();
            })
            ->editColumn('created_at', function ($item) {
                return date_from_database($item->created_at, config('core.base.general.date_format.date'));
            });

        return apply_filters(BASE_FILTER_GET_LIST_DATA, $data, $this->repository->getModel())
            ->addColumn('operations', function ($item) {
                return table_actions('property.edit', 'property.destroy', $item);
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
        $query = $model
                    ->select([
                        're_properties.id',
                        're_properties.name',
                        're_properties.status',
                        're_properties.company_id',
                        're_properties.date_deed',
                        're_properties.created_at'
                    ]);

        if (!Auth::user()->inRole('admin') && !Auth::user()->isSuperUser()) {
            $query->where('company_id', '=', Auth::user()->company_id);
        }

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
                'name'  => 're_properties.id',
                'title' => trans('core/base::tables.id'),
                'class' => 'text-center',
                'width' => '20px',
            ],
            'name'       => [
                'name'  => 're_properties.name',
                'title' => trans('core/base::tables.name'),
                'class' => 'text-left',
                'width' => '150px',
            ],
            'company_id' => [
                'name'  => 're_properties.company_id',
                'title' => trans('core/acl::company.form.label'),
                'class' => 'text-center',
                'width' => '150px',
            ],
            'date_deed'  => [
                'name'  => 're_properties.company_id',
                'title' => trans('plugins/real-estate::property.table.date_deed'),
                'class' => 'text-center',
            ],
            'status'     => [
                'name'  => 're_properties.status',
                'title' => trans('core/base::tables.status'),
                'width' => '100px',
                'class' => 'text-center',
            ],
            'created_at' => [
                'name'  => 're_properties.created_at',
                'title' => trans('core/base::tables.created_at'),
                'width' => '100px',
                'class' => 'text-center',
            ],
        ];
    }

    /**
     * @return array
     *
     * @throws Throwable
     * @since 2.1
     */
    public function buttons()
    {
        $buttons = $this->addCreateButton(route('property.create'), 'property.create');

        return apply_filters(BASE_FILTER_TABLE_BUTTONS, $buttons, Property::class);
    }

    /**
     * @return array
     * @throws Throwable
     */
    public function bulkActions(): array
    {
        return $this->addDeleteAction(route('property.deletes'), 'property.destroy', parent::bulkActions());
    }

    /**
     * @return array
     */
    public function getBulkChanges(): array
    {
        return [
            're_properties.name'       => [
                'title'    => trans('core/base::tables.name'),
                'type'     => 'text',
                'validate' => 'required|max:120',
            ],
            're_properties.status'     => [
                'title'    => trans('core/base::tables.status'),
                'type'     => 'select',
                'choices'  => PropertyStatusEnum::labels(),
                'validate' => 'required|' . Rule::in(PropertyStatusEnum::values()),
            ],
            're_properties.date_deed'  => [
                'title'    => trans('plugins/real-estate::property.table.date_deed'),
                'type'     => 'date',
            ],
            're_properties.created_at' => [
                'title' => trans('core/base::tables.created_at'),
                'type'  => 'date',
            ],
        ];
    }
}
