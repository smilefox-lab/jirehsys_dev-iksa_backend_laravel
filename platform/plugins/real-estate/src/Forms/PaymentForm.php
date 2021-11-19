<?php

namespace Botble\RealEstate\Forms;

use Assets;
use Botble\Base\Forms\FormAbstract;
use Botble\RealEstate\Forms\Fields\ContractField;
use Botble\RealEstate\Forms\Fields\PropertyField;
use Botble\RealEstate\Http\Requests\PaymentRequest;
use Botble\RealEstate\Models\Payment;
use Botble\RealEstate\Repositories\Interfaces\PropertyInterface;
use Throwable;

class PaymentForm extends FormAbstract
{
    /**
     * ProjectForm constructor.
     * @param PropertyInterface $propertyRepository
     */
    public function __construct(
        PropertyInterface $propertyRepository
    ) {
        parent::__construct();
        $this->propertyRepository = $propertyRepository;
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

        $property = $this->propertyRepository->pluck('re_properties.name', 're_properties.id');

        $this
            ->setupModel(new Payment)
            ->setValidatorClass(PaymentRequest::class)
            ->withCustomFields()
            ->addCustomField('property', PropertyField::class)
            ->addCustomField('contract', ContractField::class)
            ->add('rowOpen1', 'html', [
                'html' => '<div class="row">',
            ])
            ->add('property', 'property', [
                'label'      => trans('plugins/real-estate::payment.form.property_id'),
                'label_attr' => ['class' => 'control-label required'],
                'wrapper'    => [
                    'class' => 'form-group col-md-6',
                ],
                'choices'    => $this->getModel()->contract_id ?
                    [
                        $this->model->contract->property->id => $this->model->contract->property->name,
                    ]
                    : $property
            ])
            ->add('contract_id', 'contract', [
                'label'      => trans('plugins/real-estate::payment.form.contract_id'),
                'label_attr' => ['class' => 'control-label required'],
                'wrapper'    => [
                    'class' => 'form-group col-md-6',
                ],
                'attr'       => [
                    'placeholder' => trans('plugins/real-estate::payment.form.select_contract'),
                ],
                'choices'    => $this->getModel()->contract_id ?
                    [
                        $this->model->contract->id => $this->model->contract->name,
                    ]
                    : []
            ])
            ->add('rowClose1', 'html', [
                'html' => '</div>',
            ])

            ->add('rowOpen2', 'html', [
                'html' => '<div class="row">',
            ])
            ->add('date', 'date', [
                'label'      => trans('plugins/real-estate::payment.form.date'),
                'label_attr' => ['class' => 'control-label required'],
                'wrapper'    => [
                    'class' => 'form-group col-md-6',
                ],
                'attr'       => [
                    'placeholder' => trans('plugins/real-estate::payment.form.date'),
                ],
            ])
            ->add('amount', 'text', [
                'label'      => trans('plugins/real-estate::payment.form.amount'),
                'label_attr' => ['class' => 'control-label'],
                'wrapper'    => [
                    'class' => 'form-group col-md-6',
                ],
                'attr'       => [
                    'id'           => 'amount-number',
                    'placeholder'  => trans('plugins/real-estate::payment.form.amount'),
                    'class'        => 'form-control input-mask-number',
                    'data-counter' => 15,
                ],
            ])
            ->add('rowClose2', 'html', [
                'html' => '</div>',
            ]);
    }
}
