<?php

namespace Botble\Location\Tables;

use Auth;
use Botble\Location\Enums\DefaultStatusEnum;
use Botble\Location\Models\Region;
use Botble\Location\Repositories\Interfaces\CountryInterface;
use Botble\Location\Repositories\Interfaces\RegionInterface;
use Botble\Table\Abstracts\TableAbstract;
use Html;
use Illuminate\Contracts\Routing\UrlGenerator;
use Yajra\DataTables\DataTables;

class RegionTable extends TableAbstract
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
     * @var CountryInterface
     */
    protected $countryRepository;

    /**
     * @var RegionInterface
     */
    protected $regionRepository;

    /**
     * RegionTable constructor.
     * @param DataTables $table
     * @param UrlGenerator $urlGenerator
     * @param CountryInterface $countryRepository
     * @param RegionInterface $regionRepository
     */
    public function __construct(
        DataTables $table,
        UrlGenerator $urlGenerator,
        CountryInterface $countryRepository,
        RegionInterface $regionRepository
    ) {
        $this->repository = $regionRepository;
        $this->countryRepository = $countryRepository;
        $this->setOption('id', 'table-plugins-region');
        parent::__construct($table, $urlGenerator);

        if (!Auth::user()->hasAnyPermission(['region.edit', 'region.destroy'])) {
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
                if (!Auth::user()->hasPermission('region.edit')) {
                    return $item->name;
                }
                return Html::link(route('region.edit', $item->id), $item->name);
            })
            ->editColumn('country_id', function ($item) {
                if (!$item->country_id && $item->country->name) {
                    return null;
                }
                return Html::link(route('country.edit', $item->country_id), $item->country->name);
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
                return table_actions('region.edit', 'region.destroy', $item);
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
        $query = $model
                    ->select([
                        'regions.id',
                        'regions.name',
                        'regions.created_at',
                        'regions.status',
                        'regions.country_id'
                    ]);

        return $this->applyScopes(apply_filters(BASE_FILTER_TABLE_QUERY, $query, $model));
    }

    /**
     * {@inheritDoc}
     */
    public function columns()
    {
        return [
            'id'         => [
                'name'  => 'regions.id',
                'title' => trans('core/base::tables.id'),
                'width' => '20px',
            ],
            'name'       => [
                'name'  => 'regions.name',
                'title' => trans('core/base::tables.name'),
                'class' => 'text-left',
            ],
            'country_id' => [
                'name'  => 'regions.country_id',
                'title' => trans('plugins/location::region.country'),
                'class' => 'text-left',
            ],
            'created_at' => [
                'name'  => 'regions.created_at',
                'title' => trans('core/base::tables.created_at'),
                'width' => '100px',
            ],
            'status'     => [
                'name'  => 'regions.status',
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
        $buttons = $this->addCreateButton(route('region.create'), 'region.create');

        return apply_filters(BASE_FILTER_TABLE_BUTTONS, $buttons, Region::class);
    }

    /**
     * {@inheritDoc}
     */
    public function bulkActions(): array
    {
        return $this->addDeleteAction(route('region.deletes'), 'region.destroy', parent::bulkActions());
    }

    /**
     * {@inheritDoc}
     */
    public function getBulkChanges(): array
    {
        return [
            'regions.name'       => [
                'title'    => trans('core/base::tables.name'),
                'type'     => 'text',
                'validate' => 'required|max:120',
            ],
            'regions.country_id' => [
                'title'    => trans('plugins/location::region.country'),
                'type'     => 'select',
                'choices'  => $this->countryRepository->pluck('name', 'id'),
                'validate' => 'required|max:120',
            ],
            'regions.status'     => [
                'title'    => trans('core/base::tables.status'),
                'type'     => 'select',
                'choices'  => DefaultStatusEnum::labels(),
                'validate' => 'required|in:' . implode(',', DefaultStatusEnum::values()),
            ],
            'regions.created_at' => [
                'title' => trans('core/base::tables.created_at'),
                'type'  => 'date',
            ],
        ];
    }
}
