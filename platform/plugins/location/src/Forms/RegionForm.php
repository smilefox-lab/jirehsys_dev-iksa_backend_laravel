<?php

namespace Botble\Location\Forms;

use Botble\Base\Forms\FormAbstract;
use Botble\Location\Enums\DefaultStatusEnum;
use Botble\Location\Http\Requests\RegionRequest;
use Botble\Location\Models\Region;
use Botble\Location\Repositories\Interfaces\CountryInterface;

class RegionForm extends FormAbstract
{

    /**
     * @var CountryInterface
     */
    protected $countryRepository;

    /**
     * RegionForm constructor.
     * @param CountryInterface $countryRepository
     */
    public function __construct(CountryInterface $countryRepository)
    {
        parent::__construct();

        $this->countryRepository = $countryRepository;
    }

    /**
     * {@inheritDoc}
     */
    public function buildForm()
    {

        $countries = $this->countryRepository->advancedGet([
            'condition' => [
                'status' => DefaultStatusEnum::ENABLED,
            ],
            'order_by'   => ['order' => 'DESC'],
        ])->pluck('name', 'id')->toArray();

        $this
            ->setupModel(new Region)
            ->setValidatorClass(RegionRequest::class)
            ->withCustomFields()
            ->add('name', 'text', [
                'label'      => trans('core/base::forms.name'),
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'placeholder'  => trans('core/base::forms.name_placeholder'),
                    'data-counter' => 120,
                ],
            ])
            ->add('country_id', 'customSelect', [
                'label'      => trans('plugins/location::region.country'),
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'class' => 'form-control select-search-full',
                ],
                'choices'    => $countries,
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
