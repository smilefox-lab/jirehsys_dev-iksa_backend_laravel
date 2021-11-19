<?php

namespace Botble\Location\Tables;

use Auth;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Location\Repositories\Interfaces\CountryInterface;
use Botble\Location\Repositories\Interfaces\StateInterface;
use Botble\Table\Abstracts\TableAbstract;
use Html;
use Illuminate\Contracts\Routing\UrlGenerator;
use Yajra\DataTables\DataTables;
use Botble\Location\Models\State;

class StateTable extends TableAbstract
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
     * StateTable constructor.
     * @param DataTables $table
     * @param UrlGenerator $urlGenerator
     * @param StateInterface $stateRepository
     */
    public function __construct(
        DataTables $table,
        UrlGenerator $urlGenerator,
        StateInterface $stateRepository,
        CountryInterface $countryRepository
    ) {
        $this->repository = $stateRepository;
        $this->countryRepository = $countryRepository;
        $this->setOption('id', 'table-plugins-state');
        parent::__construct($table, $urlGenerator);

        if (!Auth::user()->hasAnyPermission(['state.edit', 'state.destroy'])) {
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
                if (!Auth::user()->hasPermission('state.edit')) {
                    return $item->name;
                }
                return Html::link(route('state.edit', $item->id), $item->name);
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
                return table_actions('state.edit', 'state.destroy', $item);
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
            'states.id',
            'states.name',
            'states.country_id',
            'states.created_at',
            'states.status',
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
                'name'  => 'states.id',
                'title' => trans('core/base::tables.id'),
                'width' => '20px',
            ],
            'name'       => [
                'name'  => 'states.name',
                'title' => trans('core/base::tables.name'),
                'class' => 'text-left',
            ],
            'country_id' => [
                'name'  => 'states.country_id',
                'title' => trans('plugins/location::state.country'),
                'class' => 'text-left',
            ],
            'created_at' => [
                'name'  => 'states.created_at',
                'title' => trans('core/base::tables.created_at'),
                'width' => '100px',
            ],
            'status'     => [
                'name'  => 'states.status',
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
        $buttons = $this->addCreateButton(route('state.create'), 'state.create');

        return apply_filters(BASE_FILTER_TABLE_BUTTONS, $buttons, State::class);
    }

    /**
     * {@inheritDoc}
     */
    public function bulkActions(): array
    {
        return $this->addDeleteAction(route('state.deletes'), 'state.destroy', parent::bulkActions());
    }

    /**
     * {@inheritDoc}
     */
    public function getBulkChanges(): array
    {
        return [
            'states.name'       => [
                'title'    => trans('core/base::tables.name'),
                'type'     => 'text',
                'validate' => 'required|max:120',
            ],
            'states.country_id' => [
                'title'    => trans('plugins/location::state.country'),
                'type'     => 'select',
                'validate' => 'required|max:120',
            ],
            'states.status'     => [
                'title'    => trans('core/base::tables.status'),
                'type'     => 'select',
                'choices'  => BaseStatusEnum::labels(),
                'validate' => 'required|in:' . implode(',', BaseStatusEnum::values()),
            ],
            'states.created_at' => [
                'title' => trans('core/base::tables.created_at'),
                'type'  => 'date',
            ],
        ];
    }
}
