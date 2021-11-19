<?php

namespace Botble\RealEstate\Tables;

use Botble\RealEstate\Enums\ProjectStatusEnum;
use Botble\RealEstate\Models\Project;
use Botble\RealEstate\Repositories\Interfaces\ProjectInterface;
use Botble\Table\Abstracts\TableAbstract;
use Html;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;
use Throwable;
use Yajra\DataTables\DataTables;

class ProjectTable extends TableAbstract
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
     * @param ProjectInterface $projectRepository
     */
    public function __construct(DataTables $table, UrlGenerator $urlGenerator, ProjectInterface $projectRepository)
    {
        $this->repository = $projectRepository;
        $this->setOption('id', 'table-plugins-real-estate-projects');
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
                return anchor_link(route('project.edit', $item->id), $item->name);
            })
            ->editColumn('image', function ($item) {
                return Html::image(get_object_image($item->image, 'thumb'), $item->name, ['width' => 50]);
            })
            ->editColumn('checkbox', function ($item) {
                return table_checkbox($item->id);
            })
            ->editColumn('status', function ($item) {
                return $item->status->toHtml();
            });

        return apply_filters(BASE_FILTER_GET_LIST_DATA, $data, $this->repository->getModel())
            ->addColumn('operations', function ($item) {
                return table_actions('project.edit', 'project.destroy', $item);
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
            're_projects.id',
            're_projects.name',
            're_projects.images',
            're_projects.status',
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
            'id'     => [
                'name'  => 're_projects.id',
                'title' => trans('core/base::tables.id'),
                'width' => '20px',
            ],
            'image'  => [
                'name'  => 're_projects.image',
                'title' => trans('core/base::tables.image'),
                'width' => '60px',
            ],
            'name'   => [
                'name'  => 're_projects.name',
                'title' => trans('core/base::tables.name'),
                'class' => 'text-left',
            ],
            'status' => [
                'name'  => 're_projects.status',
                'title' => trans('core/base::tables.status'),
                'width' => '100px',
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
        $buttons = $this->addCreateButton(route('project.create'), 'project.create');

        return apply_filters(BASE_FILTER_TABLE_BUTTONS, $buttons, Project::class);
    }

    /**
     * @return array
     * @throws Throwable
     */
    public function bulkActions(): array
    {
        return $this->addDeleteAction(route('project.deletes'), 'project.destroy', parent::bulkActions());
    }

    /**
     * @return mixed
     */
    public function getBulkChanges(): array
    {
        return [
            're_projects.name'   => [
                'title'    => trans('core/base::tables.name'),
                'type'     => 'text',
                'validate' => 'required|max:120',
            ],
            're_projects.status' => [
                'title'    => trans('core/base::tables.status'),
                'type'     => 'select',
                'choices'  => ProjectStatusEnum::labels(),
                'validate' => 'required|' . Rule::in(ProjectStatusEnum::values()),
            ],
        ];
    }
}
