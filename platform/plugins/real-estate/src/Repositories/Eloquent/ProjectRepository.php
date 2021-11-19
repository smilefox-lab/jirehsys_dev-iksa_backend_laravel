<?php

namespace Botble\RealEstate\Repositories\Eloquent;

use Botble\RealEstate\Enums\ProjectStatusEnum;
use Botble\RealEstate\Repositories\Interfaces\ProjectInterface;
use Botble\Support\Repositories\Eloquent\RepositoriesAbstract;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;

class ProjectRepository extends RepositoriesAbstract implements ProjectInterface
{
    /**
     * {@inheritdoc}
     */
    public function getProjects($filters = [], $params = [])
    {
        $filters = array_merge([
            'keyword'     => null,
            'city'        => null,
            'min_floor'   => null,
            'max_floor'   => null,
            'blocks'      => null,
            'min_flat'    => null,
            'max_flat'    => null,
            'category_id' => null,
        ], $filters);

        $params = array_merge([
            'condition' => [],
            'order_by'  => [
                're_projects.created_at' => 'DESC',
            ],
            'take'      => null,
            'paginate'  => [
                'per_page'      => 10,
                'current_paged' => 1,
            ],
            'select'    => [
                're_projects.*',
            ],
            'with'      => [],
        ], $params);

        $this->model = $this->originalModel;

        if ($filters['keyword'] !== null) {
            $this->model = $this->model
                ->where(function (Builder $query) use ($filters) {
                    return $query
                        ->where('re_projects.name', 'LIKE', '%' . $filters['keyword'] . '%')
                        ->orWhere('re_projects.location', 'LIKE', '%' . $filters['keyword'] . '%');
                });
        }

        if ($filters['city'] !== null) {
            $this->model = $this->model
                ->join('cities', 'cities.id', '=', 're_projects.city_id')
                ->where('cities.slug', $filters['city']);
        }

        if ($filters['blocks']) {
            if ($filters['blocks'] < 5) {
                $this->model = $this->model->where('re_projects.number_block', $filters['blocks']);
            } else {
                $this->model = $this->model->where('re_projects.number_block', '>=', $filters['blocks']);
            }
        }

        if ($filters['min_floor'] !== null || $filters['max_floor'] !== null) {
            $this->model = $this->model
                ->where(function ($query) use ($filters) {
                    $minFloor = Arr::get($filters, 'min_floor');
                    $maxFloor = Arr::get($filters, 'max_floor');

                    /**
                     * @var \Illuminate\Database\Query\Builder $query
                     */
                    if ($minFloor !== null) {
                        $query = $query->where('re_projects.number_floor', '>=', $minFloor);
                    }

                    if ($maxFloor !== null) {
                        $query = $query->where('re_projects.number_floor', '<=', $maxFloor);
                    }

                    return $query;
                });
        }

        if ($filters['min_flat'] !== null || $filters['max_flat'] !== null) {
            $this->model = $this->model
                ->where(function ($query) use ($filters) {
                    $minFlat = Arr::get($filters, 'min_flat');
                    $maxFlat = Arr::get($filters, 'max_flat');

                    /**
                     * @var \Illuminate\Database\Query\Builder $query
                     */
                    if ($minFlat !== null) {
                        $query = $query->where('re_projects.number_flat', '>=', $minFlat);
                    }

                    if ($maxFlat !== null) {
                        $query = $query->where('re_projects.number_flat', '<=', $maxFlat);
                    }

                    return $query;
                });
        }

        if ($filters['category_id'] !== null) {
            $this->model = $this->model->where('re_projects.category_id', $filters['category_id']);
        }

        $this->model->where('re_projects.status', ProjectStatusEnum::SELLING);

        return $this->advancedGet($params);
    }

    /**
     * {@inheritdoc}
     */
    public function getRelatedProjects(int $projectId, $limit = 4)
    {
        $this->model = $this->originalModel;
        $this->model = $this->model
            ->where('id', '<>', $projectId);

        $params = [
            'condition' => [],
            'order_by'  => [
                'created_at' => 'DESC',
            ],
            'take'      => $limit,
            'paginate'  => [
                'per_page'      => 12,
                'current_paged' => 1,
            ],
            'select'    => [
                're_projects.*',
            ],
            'with'      => [],
        ];

        return $this->advancedGet($params);
    }
}
