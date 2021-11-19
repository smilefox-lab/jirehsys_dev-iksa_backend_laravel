<?php

namespace Botble\Location\Forms;

use Botble\Base\Forms\FormAbstract;
use Botble\Location\Enums\DefaultStatusEnum;
use Botble\Location\Http\Requests\CommuneRequest;
use Botble\Location\Models\Commune;
use Botble\Location\Repositories\Interfaces\RegionInterface;

class CommuneForm extends FormAbstract
{

    /**
     * @var RegionInterface
     */
    protected $regionRepository;

    /**
     * CommuneForm constructor.
     * @param RegionInterface $regionRepository
     */
    public function __construct(RegionInterface $regionRepository)
    {
        parent::__construct();

        $this->regionRepository = $regionRepository;
    }

    /**
     * {@inheritDoc}
     */
    public function buildForm()
    {
        $regions = $this->regionRepository->advancedGet([
            'condition' => [
                'status' => DefaultStatusEnum::ENABLED,
            ],
            'order_by'   => ['order' => 'DESC'],
        ])->pluck('name', 'id')->toArray();

        $this
            ->setupModel(new Commune)
            ->setValidatorClass(CommuneRequest::class)
            ->withCustomFields()
            ->add('name', 'text', [
                'label'      => trans('core/base::forms.name'),
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'placeholder'  => trans('core/base::forms.name_placeholder'),
                    'data-counter' => 120,
                ],
            ])
            ->add('region_id', 'customSelect', [
                'label'      => trans('plugins/location::commune.region'),
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'class' => 'form-control select-search-full',
                ],
                'choices'    => $regions,
            ])
            ->add('order', 'number', [
                'label'         => trans('core/base::forms.order'),
                'label_attr'    => ['class' => 'control-label'],
                'attr'          => [
                    'placeholder' => trans('core/base::forms.order_by_placeholder'),
                ],
                'default_value' => 0,
            ])
            ->add('is_default', 'onOff', [
                'label'         => trans('core/base::forms.is_default'),
                'label_attr'    => ['class' => 'control-label'],
                'default_value' => false,
            ])
            ->add('status', 'customSelect', [
                'label'      => trans('core/base::tables.status'),
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'class' => 'form-control select-full',
                ],
                'choices'    => DefaultStatusEnum::labels(),
            ])
            ->setBreakFieldPoint('status');
    }
}
