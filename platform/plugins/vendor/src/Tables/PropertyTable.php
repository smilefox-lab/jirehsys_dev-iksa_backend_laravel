<?php

namespace Botble\Vendor\Tables;

use Auth;
use Botble\Vendor\Models\Vendor;
use Html;
use Illuminate\Support\Arr;

class PropertyTable extends \Botble\RealEstate\Tables\PropertyTable
{
    /**
     * @var bool
     */
    public $hasActions = false;

    /**
     * @var bool
     */
    public $hasCheckbox = false;

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
            ->editColumn('name', function ($item) {
                return anchor_link(route('public.vendor.properties.edit', $item->id), $item->name);
            })
            ->editColumn('image', function ($item) {
                return Html::image(get_object_image($item->image, 'thumb'), $item->name, ['width' => 50]);
            })
            ->editColumn('checkbox', function ($item) {
                return table_checkbox($item->id);
            })
            ->editColumn('created_at', function ($item) {
                return date_from_database($item->created_at, config('core.base.general.date_format.date'));
            })
            ->editColumn('expire_date', function ($item) {
                if ($item->never_expired) {
                    return __('Never expired');
                }

                if ($item->expire_date->isPast()) {
                    return Html::tag('span', $item->expire_date->toDateString(), ['class' => 'text-danger'])->toHtml();
                }

                if (now()->diffInDays($item->expire_date) < 3) {
                    return Html::tag('span', $item->expire_date->toDateString(), ['class' => 'text-warning'])->toHtml();
                }

                return $item->expire_date->toDateString();
            })
            ->editColumn('status', function ($item) {
                return $item->status->toHtml();
            })
            ->editColumn('moderation_status', function ($item) {
                return $item->moderation_status->toHtml();
            });

        return apply_filters(BASE_FILTER_GET_LIST_DATA, $data, $this->repository->getModel())
            ->addColumn('operations', function ($item) {
                $edit = 'public.vendor.properties.edit';
                $delete = 'public.vendor.properties.destroy';

                return view('plugins/vendor::table.actions', compact('edit', 'delete', 'item'))->render();
            })
            ->escapeColumns([])
            ->make(true);
    }

    /**
     * {@inheritdoc}
     */
    public function query()
    {
        $model = $this->repository->getModel();
        $query = $model
            ->select([
                're_properties.id',
                're_properties.name',
                're_properties.images',
                're_properties.created_at',
                're_properties.status',
                're_properties.moderation_status',
                're_properties.expire_date',
            ])
            ->where([
                're_properties.author_id'   => auth()->guard('vendor')->user()->getKey(),
                're_properties.author_type' => Vendor::class,
            ]);

        return $this->applyScopes(apply_filters(BASE_FILTER_TABLE_QUERY, $query, $model));
    }

    /**
     * {@inheritdoc}
     */
    public function buttons()
    {
        $buttons = [];
        if (Auth::guard('vendor')->user()->canPost()) {
            $buttons = $this->addCreateButton(route('public.vendor.properties.create'), null);
        }

        return apply_filters(BASE_FILTER_TABLE_BUTTONS, $buttons, Vendor::class);
    }

    /**
     * @return array
     */
    public function columns()
    {
        $columns = parent::columns();
        Arr::forget($columns, 'author_id');

        $columns['expire_date'] = [
            'name'  => 'jb_jobs.expire_date',
            'title' => __('Expire date'),
            'width' => '150px',
        ];

        return $columns;
    }

    /**
     * @return array
     */
    public function getDefaultButtons(): array
    {
        return ['reload'];
    }
}
