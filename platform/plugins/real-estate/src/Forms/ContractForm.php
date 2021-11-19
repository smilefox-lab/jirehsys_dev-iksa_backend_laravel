<?php

namespace Botble\RealEstate\Forms;

use Assets;
use Botble\Base\Forms\FormAbstract;
use Botble\RealEstate\Enums\PropertyStatusEnum;
use Botble\RealEstate\Http\Requests\ContractRequest;
use Botble\RealEstate\Models\Contract;
use Botble\RealEstate\Repositories\Interfaces\LesseeInterface;
use Botble\RealEstate\Repositories\Interfaces\PropertyInterface;
use Throwable;

class ContractForm extends FormAbstract
{
    /**
     * ProjectForm constructor.
     * @param PropertyInterface $propertyRepository
     * @param LesseeInterface $lesseeRepository
     */
    public function __construct(
        PropertyInterface $propertyRepository,
        LesseeInterface $lesseeRepository
    ) {
        parent::__construct();
        $this->propertyRepository = $propertyRepository;
        $this->lesseeRepository = $lesseeRepository;
    }

    /**
     * @return mixed|void
     * @throws Throwable
     */
    public function buildForm()
    {
        Assets::addStyles(['datetimepicker'])
            ->addScripts(['input-mask'])
            ->addScriptsDirectly('vendor/core/plugins/real-estate/js/real-estate.js')
            ->addStylesDirectly('vendor/core/plugins/real-estate/css/real-estate.css');


        $properties = $this->propertyRepository
                        ->pluck('name', 'id');
        $lessees = $this->lesseeRepository->pluck('rut', 'id');

        $this
            ->setupModel(new Contract)
            ->setValidatorClass(ContractRequest::class)
            ->withCustomFields()
            ->add('name', 'text', [
                'label'      => trans('plugins/real-estate::contract.form.name'),
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'placeholder'  => trans('plugins/real-estate::contract.form.name'),
                    'data-counter' => 120,
                ],
            ])
            ->add('rowOpen1', 'html', [
                'html' => '<div class="row">',
            ])
            ->add('property_id', 'customSelect', [
                'label'      => trans('plugins/real-estate::contract.form.property'),
                'label_attr' => ['class' => 'control-label required'],
                'wrapper'    => [
                    'class' => 'form-group col-md-6',
                ],
                'attr'       => [
                    'class' => 'form-control select-search-full',
                ],
                'choices'    => [null => trans('plugins/real-estate::contract.form.select_property')] + $properties,
            ])
            ->add('lessee_id', 'customSelect', [
                'label'      => trans('plugins/real-estate::contract.form.lessee'),
                'label_attr' => ['class' => 'control-label required'],
                'wrapper'    => [
                    'class' => 'form-group col-md-6',
                ],
                'attr'       => [
                    'class' => 'form-control select-search-full',
                ],
                'choices'    => [null => trans('plugins/real-estate::contract.form.select_lessee')] + $lessees,
            ])
            ->add('rowClose1', 'html', [
                'html' => '</div>',
            ])

            ->add('rowOpen2', 'html', [
                'html' => '<div class="row">',
            ])
            ->add('start_date', 'date', [
                'label'      => trans('plugins/real-estate::contract.form.start_date'),
                'label_attr' => ['class' => 'control-label required'],
                'wrapper'    => [
                    'class' => 'form-group col-md-4',
                ],
                'attr'       => [
                    'placeholder' => trans('plugins/real-estate::contract.form.start_date'),
                ],
            ])
            ->add('end_date', 'date', [
                'label'      => trans('plugins/real-estate::contract.form.end_date'),
                'label_attr' => ['class' => 'control-label required'],
                'wrapper'    => [
                    'class' => 'form-group col-md-4',
                ],
                'attr'       => [
                    'placeholder' => trans('plugins/real-estate::contract.form.end_date'),
                ],
            ])
            ->add('cutoff_date', 'date', [
                'label'      => trans('plugins/real-estate::contract.form.cutoff_date'),
                'label_attr' => ['class' => 'control-label required'],
                'wrapper'    => [
                    'class' => 'form-group col-md-4',
                ],
                'attr'       => [
                    'placeholder' => trans('plugins/real-estate::contract.form.cutoff_date'),
                ],
            ])
            ->add('rowClose2', 'html', [
                'html' => '</div>',
            ])

            ->add('rowOpen3', 'html', [
                'html' => '<div class="row">',
            ])
            ->add('quota', 'text', [
                'label'      => trans('plugins/real-estate::contract.form.quota'),
                'label_attr' => ['class' => 'control-label required'],
                'wrapper'    => [
                    'class' => 'form-group col-md-4',
                ],
                'attr'       => [
                    'id'           => 'quota-number',
                    'placeholder'  => trans('plugins/real-estate::contract.form.quota'),
                    'class'        => 'form-control input-mask-number',
                ],
            ])
            ->add('contribution_quota', 'text', [
                'label'      => trans('plugins/real-estate::contract.form.contribution_quota'),
                'label_attr' => ['class' => 'control-label'],
                'wrapper'    => [
                    'class' => 'form-group col-md-4',
                ],
                'attr'       => [
                    'id'          => 'contribution_quota-number',
                    'placeholder' => trans('plugins/real-estate::contract.form.contribution_quota'),
                    'class'       => 'form-control input-mask-number',
                ],
            ])
            ->add('contribution', 'text', [
                'label'      => trans('plugins/real-estate::contract.form.contribution'),
                'label_attr' => ['class' => 'control-label'],
                'wrapper'    => [
                    'class' => 'form-group col-md-4',
                ],
                'attr'       => [
                    'id'          => 'contribution-number',
                    'placeholder' => trans('plugins/real-estate::contract.form.contribution'),
                    'class'       => 'form-control input-mask-number',
                ],
            ])
            ->add('rowClose3', 'html', [
                'html' => '</div>',
            ])

            ->add('rowOpen4', 'html', [
                'html' => '<div class="row">',
            ])

            ->add('income', 'text', [
                'label'      => trans('plugins/real-estate::contract.form.income'),
                'label_attr' => ['class' => 'control-label'],
                'wrapper'    => [
                    'class' => 'form-group col-md-4',
                ],
                'attr'       => [
                    'id'          => 'income-number',
                    'placeholder' => trans('plugins/real-estate::contract.form.income'),
                    'class'       => 'form-control input-mask-number',
                ],
            ])
            ->add('rowClose4', 'html', [
                'html' => '</div>',
            ]);


    }
}
