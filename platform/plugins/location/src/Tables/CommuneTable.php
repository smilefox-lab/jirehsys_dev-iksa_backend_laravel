<?php

namespace Botble\Location\Tables;

use Auth;
use Botble\Location\Enums\DefaultStatusEnum;
use Botble\Location\Models\Commune;
use Botble\Location\Repositories\Interfaces\CommuneInterface;
use Botble\Location\Repositories\Interfaces\RegionInterface;
use Botble\Table\Abstracts\TableAbstract;
use Html;
use Illuminate\Contracts\Routing\UrlGenerator;
use Yajra\DataTables\DataTables;

class CommuneTable extends TableAbstract
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
     * @var RegionInterface
     */
    protected $regionRepository;

    /**
     * CommuneTable constructor.
     * @param DataTables $table
     * @param UrlGenerator $urlGenerator
     * @param CommuneInterface $communeRepository
     * @param RegionInterface $regionRepository
     */
    public function __construct(
        DataTables $table,
        UrlGenerator $urlGenerator,
        CommuneInterface $communeRepository,
        RegionInterface $regionRepository
    ) {
        $this->repository = $communeRepository;
        $this->regionRepository = $regionRepository;
        $this->setOption('id', 'table-plugins-commune');
        parent::__construct($table, $urlGenerator);

        if (!Auth::user()->hasAnyPermission(['commune.edit', 'commune.destroy'])) {
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
                if (!Auth::user()->hasPermission('commune.edit')) {
                    return $item->name;
                }
                return Html::link(route('commune.edit', $item->id), $item->name);
            })
            ->editColumn('region_id', function ($item) {
                if (!$item->region_id && $item->region->name) {
                    return null;
                }
                return Html::link(route('region.edit', $item->region_id), $item->region->name);
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
                return table_actions('commune.edit', 'commune.destroy', $item);
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
            'communes.id',
            'communes.name',
            'communes.created_at',
            'communes.status',
            'communes.region_id'
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
                'name'  => 'communes.id',
                'title' => trans('core/base::tables.id'),
                'width' => '20px',
            ],
            'name'       => [
                'name'  => 'communes.name',
                'title' => trans('core/base::tables.name'),
                'class' => 'text-left',
            ],
            'region_id'   => [
                'name'  => 'communes.region_id',
                'title' => trans('plugins/location::commune.region'),
                'class' => 'text-left',
            ],
            'created_at' => [
                'name'  => 'communes.created_at',
                'title' => trans('core/base::tables.created_at'),
                'width' => '100px',
            ],
            'status'     => [
                'name'  => 'communes.status',
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
        $buttons = $this->addCreateButton(route('commune.create'), 'commune.create');

        return apply_filters(BASE_FILTER_TABLE_BUTTONS, $buttons, Commune::class);
    }

    /**
     * {@inheritDoc}
     */
    public function bulkActions(): array
    {
        return $this->addDeleteAction(route('commune.deletes'), 'commune.destroy', parent::bulkActions());
    }

    /**
     * {@inheritDoc}
     */
    public function getBulkChanges(): array
    {
        return [
            'communes.name'       => [
                'title'    => trans('core/base::tables.name'),
                'type'     => 'text',
                'validate' => 'required|max:120',
            ],
            'communes.region_id'   => [
                'title'    => trans('plugins/location::commune.region'),
                'type'     => 'select',
                'choices'  => $this->regionRepository->pluck('name', 'id'),
                'validate' => 'required|max:120',
            ],
            'communes.status'     => [
                'title'    => trans('core/base::tables.status'),
                'type'     => 'select',
                'choices'  => DefaultStatusEnum::labels(),
                'validate' => 'required|in:' . implode(',', DefaultStatusEnum::values()),
            ],
            'communes.created_at' => [
                'title' => trans('core/base::tables.created_at'),
                'type'  => 'date',
            ],
        ];
    }
}
