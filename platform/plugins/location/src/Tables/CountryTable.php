<?php

namespace Botble\Location\Tables;

use Auth;
use Botble\Location\Enums\DefaultStatusEnum;
use Botble\Location\Repositories\Interfaces\CountryInterface;
use Botble\Location\Models\Country;
use Botble\Table\Abstracts\TableAbstract;
use Html;
use Illuminate\Contracts\Routing\UrlGenerator;
use Yajra\DataTables\DataTables;

class CountryTable extends TableAbstract
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
     * CountryTable constructor.
     * @param DataTables $table
     * @param UrlGenerator $urlGenerator
     * @param CountryInterface $countryRepository
     */
    public function __construct(DataTables $table, UrlGenerator $urlGenerator, CountryInterface $countryRepository)
    {
        $this->repository = $countryRepository;
        $this->setOption('id', 'table-plugins-country');
        parent::__construct($table, $urlGenerator);

        if (!Auth::user()->hasAnyPermission(['country.edit', 'country.destroy'])) {
            $this->hasOperations = false;
            $this->hasActions = false;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function ajax()
    {
        $data = $this->table
            ->eloquent($this->query())
            ->editColumn('name', function ($item) {
                if (!Auth::user()->hasPermission('country.edit')) {
                    return $item->name;
                }
                return Html::link(route('country.edit', $item->id), $item->name);
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
                return table_actions('country.edit', 'country.destroy', $item);
            })
            ->escapeColumns([])
            ->make(true);
    }

    /**
     * {@inheritDoc}
     */
    public function query()
    {
        $model = $this->repository->getModel();
        $query = $model->select([
            'countries.id',
            'countries.name',
            'countries.nationality',
            'countries.created_at',
            'countries.status',
        ]);

        return $this->applyScopes(apply_filters(BASE_FILTER_TABLE_QUERY, $query, $model));
    }

    /**
     * {@inheritDoc}
     */
    public function columns()
    {
        return [
            'id'          => [
                'name'  => 'countries.id',
                'title' => trans('core/base::tables.id'),
                'width' => '20px',
            ],
            'name'        => [
                'name'  => 'countries.name',
                'title' => trans('core/base::tables.name'),
                'class' => 'text-left',
            ],
            'nationality' => [
                'name'  => 'countries.nationality',
                'title' => trans('plugins/location::country.nationality'),
                'class' => 'text-left',
            ],
            'created_at'  => [
                'name'  => 'countries.created_at',
                'title' => trans('core/base::tables.created_at'),
                'width' => '100px',
            ],
            'status'      => [
                'name'  => 'countries.status',
                'title' => trans('core/base::tables.status'),
                'width' => '100px',
            ],
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function buttons()
    {
        $buttons = $this->addCreateButton(route('country.create'), 'country.create');

        return apply_filters(BASE_FILTER_TABLE_BUTTONS, $buttons, Country::class);
    }

    /**
     * {@inheritDoc}
     */
    public function bulkActions(): array
    {
        return $this->addDeleteAction(route('country.deletes'), 'country.destroy', parent::bulkActions());
    }

    /**
     * {@inheritDoc}
     */
    public function getBulkChanges(): array
    {
        return [
            'countries.name'        => [
                'title'    => trans('core/base::tables.name'),
                'type'     => 'text',
                'validate' => 'required|max:120',
            ],
            'countries.nationality' => [
                'title'    => trans('plugins/location::country.nationality'),
                'type'     => 'text',
                'validate' => 'required|max:120',
            ],
            'countries.status'      => [
                'title'    => trans('core/base::tables.status'),
                'type'     => 'select',
                'choices'  => DefaultStatusEnum::labels(),
                'validate' => 'required|in:' . implode(',', DefaultStatusEnum::values()),
            ],
            'countries.created_at'  => [
                'title' => trans('core/base::tables.created_at'),
                'type'  => 'date',
            ],
        ];
    }
}
