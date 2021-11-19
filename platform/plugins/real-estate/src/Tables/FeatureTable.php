<?php

namespace Botble\RealEstate\Tables;

use Botble\RealEstate\Models\Feature;
use Botble\RealEstate\Repositories\Interfaces\FeatureInterface;
use Botble\Table\Abstracts\TableAbstract;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Throwable;
use Yajra\DataTables\DataTables;

class FeatureTable extends TableAbstract
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
     * TagTable constructor.
     * @param DataTables $table
     * @param UrlGenerator $urlGenerator
     * @param FeatureInterface $featureRepository
     */
    public function __construct(
        DataTables $table,
        UrlGenerator $urlGenerator,
        FeatureInterface $featureRepository
    ) {
        $this->repository = $featureRepository;
        $this->setOption('id', 'table-plugins-real-estate-property-feature');
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
                return anchor_link(route('property_feature.edit', $item->id), $item->name);
            })
            ->editColumn('checkbox', function ($item) {
                return table_checkbox($item->id);
            });

        return apply_filters(BASE_FILTER_GET_LIST_DATA, $data, $this->repository->getModel())
            ->addColumn('operations', function ($item) {
                return table_actions('property_feature.edit', 'property_feature.destroy', $item);
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
            're_features.id',
            're_features.name',
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
            'id'   => [
                'name'  => 're_features.id',
                'title' => trans('core/base::tables.id'),
                'width' => '20px',
            ],
            'name' => [
                'name'  => 're_features.name',
                'title' => trans('core/base::tables.name'),
                'class' => 'text-left',
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
        $buttons = $this->addCreateButton(route('property_feature.create'), 'property_feature.create');

        return apply_filters(BASE_FILTER_TABLE_BUTTONS, $buttons, Feature::class);
    }

    /**
     * @return array
     * @throws Throwable
     */
    public function bulkActions(): array
    {
        return $this->addDeleteAction(route('property_feature.deletes'), 'property_feature.destroy',
            parent::bulkActions());
    }

    /**
     * @return mixed
     */
    public function getBulkChanges(): array
    {
        return [
            're_features.name' => [
                'title'    => trans('core/base::tables.name'),
                'type'     => 'text',
                'validate' => 'required|max:120',
            ],
        ];
    }
}
