<?php

namespace Botble\RealEstate\Repositories\Eloquent;

use Auth;
use Botble\RealEstate\Enums\ModerationStatusEnum;
use Botble\RealEstate\Enums\PropertyStatusEnum;
use Botble\RealEstate\Enums\PropertyTypeEnum;
use Botble\RealEstate\Repositories\Interfaces\PropertyInterface;
use Botble\Support\Repositories\Eloquent\RepositoriesAbstract;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;

class PropertyRepository extends RepositoriesAbstract implements PropertyInterface
{
    /**
     * {@inheritdoc}
     */
    public function getRelatedProperties(int $propertyId, $limit = 4)
    {
        $this->model = $this->originalModel;
        $this->model = $this->model->where('id', '<>', $propertyId)
            ->notExpired();

        $params = [
            'condition' => [
                ['re_properties.status', 'NOT_IN', [PropertyStatusEnum::NOT_AVAILABLE]],
                're_properties.moderation_status' => ModerationStatusEnum::APPROVED,
            ],
            'order_by'  => [
                're_properties.created_at' => 'DESC',
            ],
            'take'      => $limit,
            'paginate'  => [
                'per_page'      => 12,
                'current_paged' => 1,
            ],
            'select'    => [
                're_properties.*',
            ],
            'with'      => [],
        ];

        return $this->advancedGet($params);
    }

    /**
     * {@inheritdoc}
     */
    public function getProperties($filters = [], $params = [])
    {
        $filters = array_merge([
            'keyword'     => null,
            'type'        => null,
            'bedroom'     => null,
            'bathroom'    => null,
            'floor'       => null,
            'min_square'  => null,
            'max_square'  => null,
            'min_price'   => null,
            'max_price'   => null,
            'project'     => null,
            'category_id' => null,
        ], $filters);

        $params = array_merge([
            'condition' => [],
            'order_by'  => [
                're_properties.created_at'        => 'DESC',
                're_properties.moderation_status' => ModerationStatusEnum::APPROVED,
            ],
            'take'      => null,
            'paginate'  => [
                'per_page'      => 10,
                'current_paged' => 1,
            ],
            'select'    => [
                're_properties.*',
            ],
            'with'      => [],
        ], $params);

        $this->model = $this->originalModel;

        $this->model = $this->model->notExpired();

        if ($filters['keyword'] !== null) {
            $this->model = $this->model
                ->where(function (Builder $query) use ($filters) {
                    return $query
                        ->where('re_properties.name', 'LIKE', '%' . $filters['keyword'] . '%')
                        ->orWhere('re_properties.location', 'LIKE', '%' . $filters['keyword'] . '%');
                });
        }

        if ($filters['type'] !== null) {
            if ($filters['type'] == PropertyTypeEnum::SALE) {
                $this->model = $this->model->where('re_properties.type', $filters['type'])
                    ->where('re_properties.status', PropertyStatusEnum::SELLING);
            } else {
                $this->model = $this->model->where('re_properties.type', $filters['type'])
                    ->where('re_properties.status', PropertyStatusEnum::RENTING);
            }
        }

        if ($filters['bedroom']) {
            if ($filters['bedroom'] < 5) {
                $this->model = $this->model->where('re_properties.number_bedroom', $filters['bedroom']);
            } else {
                $this->model = $this->model->where('re_properties.number_bedroom', '>=', $filters['bedroom']);
            }
        }

        if ($filters['bathroom']) {
            if ($filters['bathroom'] < 5) {
                $this->model = $this->model->where('re_properties.number_bathroom', $filters['bathroom']);
            } else {
                $this->model = $this->model->where('re_properties.number_bathroom', '>=', $filters['bathroom']);
            }
        }

        if ($filters['floor']) {
            if ($filters['floor'] < 5) {
                $this->model = $this->model->where('re_properties.number_floor', $filters['floor']);
            } else {
                $this->model = $this->model->where('re_properties.number_floor', '>=', $filters['floor']);
            }
        }

        if ($filters['min_square'] !== null || $filters['max_square'] !== null) {
            $this->model = $this->model
                ->where(function ($query) use ($filters) {
                    $minSquare = Arr::get($filters, 'min_square');
                    $maxSquare = Arr::get($filters, 'max_square');

                    /**
                     * @var \Illuminate\Database\Query\Builder $query
                     */
                    if ($minSquare !== null) {
                        $query = $query->where('re_properties.square', '>=', $minSquare);
                    }

                    if ($maxSquare !== null) {
                        $query = $query->where('re_properties.square', '<=', $maxSquare);
                    }

                    return $query;
                });
        }

        if ($filters['min_price'] !== null || $filters['max_price'] !== null) {
            $this->model = $this->model
                ->where(function ($query) use ($filters) {

                    $minPrice = Arr::get($filters, 'min_price');
                    $maxPrice = Arr::get($filters, 'max_price');

                    /**
                     * @var Builder $query
                     */
                    if ($minPrice !== null) {
                        $query = $query->where('re_properties.price', '>=', $minPrice);
                    }

                    if ($maxPrice !== null) {
                        $query = $query->where('re_properties.price', '<=', $maxPrice);
                    }

                    return $query;
                });
        }

        if ($filters['city'] !== null) {
            $this->model = $this->model
                ->join('cities', 'cities.id', '=', 're_properties.city_id')
                ->where('cities.slug', $filters['city']);
        }

        if ($filters['project'] !== null) {
            $this->model = $this->model->where('re_properties.project_id', $filters['project']);
        }

        if ($filters['category_id'] !== null) {
            $this->model = $this->model->where('re_properties.category_id', $filters['category_id']);
        }

        return $this->advancedGet($params);
    }

    /**
     * {@inheritDoc}
     */
    public function getProperty(int $propertyId, array $with = [])
    {
        $params = [
            'condition' => [
                're_properties.id'                => $propertyId,
                're_properties.moderation_status' => ModerationStatusEnum::APPROVED,
            ],
            'with'      => $with,
            'take'      => 1,
        ];

        $this->model = $this->originalModel;

        $this->model = $this->model->notExpired();

        return $this->advancedGet($params);
    }

    /**
     * {@inheritDoc}
     */
    public function getPropertiesByConditions(array $condition, $limit, array $with = [])
    {
        $this->model = $this->originalModel;

        $this->model = $this->model->notExpired();

        $params = [
            'condition' => $condition,
            'with'      => $with,
            'take'      => $limit,
            'order_by'  => ['re_properties.created_at' => 'DESC'],
        ];

        return $this->advancedGet($params);
    }

    public function getPropertiesByCompany(array $condition)
    {
        $this->model = $this->originalModel;
        if (!Auth::user()->inRole('admin') && !Auth::user()->isSuperUser()) {
            $this->model = $this->model->where('company_id', '=', Auth::user()->company_id);
        }

        $params = [
            'condition' => $condition,
            'order_by'  => ['re_properties.name' => 'ASC'],
        ];

        return $this->advancedGet($params);

    }
}
