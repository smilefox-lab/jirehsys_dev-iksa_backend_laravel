<?php

namespace Botble\RealEstate\Forms;

use Botble\Base\Forms\FormAbstract;
use Botble\RealEstate\Enums\DefaultStatusEnum;
use Botble\RealEstate\Enums\LesseeTypeEnum;
use Botble\RealEstate\Http\Requests\LesseeRequest;
use Botble\RealEstate\Models\Lessee;

class LesseeForm extends FormAbstract
{

    /**
     * @return mixed|void
     * @throws Throwable
     */
    public function buildForm()
    {
        $this
            ->setupModel(new Lessee)
            ->setValidatorClass(LesseeRequest::class)
            ->withCustomFields()
            ->add('rowOpen1', 'html', [
                'html' => '<div class="row">',
            ])
            ->add('name', 'text', [
                'label'      => trans('plugins/real-estate::lessee.form.name'),
                'label_attr' => ['class' => 'control-label required'],
                'wrapper'    => [
                    'class' => 'form-group col-md-6',
                ],
                'attr'       => [
                    'placeholder'  => trans('plugins/real-estate::lessee.form.name'),
                    'data-counter' => 120,
                ],
            ])
            ->add('rut', 'text', [
                'label'      => trans('plugins/real-estate::lessee.form.rut'),
                'label_attr' => ['class' => 'control-label required'],
                'wrapper'    => [
                    'class' => 'form-group col-md-6',
                ],
                'attr'       => [
                    'placeholder'  => trans('plugins/real-estate::lessee.form.rut_placeholder'),
                    'data-counter' => 120,
                ],
            ])
            ->add('rowClose1', 'html', [
                'html' => '</div>',
            ])

            ->add('rowOpen2', 'html', [
                'html' => '<div class="row">',
            ])
            ->add('email', 'text', [
                'label'      => trans('plugins/real-estate::lessee.form.email'),
                'label_attr' => ['class' => 'control-label required'],
                'wrapper'    => [
                    'class' => 'form-group col-md-6',
                ],
                'attr'       => [
                    'placeholder'  => trans('plugins/real-estate::lessee.form.phone'),
                    'data-counter' => 120,
                ],
            ])
            ->add('phone', 'text', [
                'label'      => trans('plugins/real-estate::lessee.form.phone'),
                'label_attr' => ['class' => 'control-label required'],
                'wrapper'    => [
                    'class' => 'form-group col-md-6',
                ],
                'attr'       => [
                    'placeholder'  => trans('plugins/real-estate::lessee.form.phone'),
                ],
            ])
            ->add('rowClose2', 'html', [
                'html' => '</div>',
            ])
            ->add('rowOpen3', 'html', [
                'html' => '<div class="row">',
            ])
            ->add('type', 'customSelect', [
                'label'      => trans('plugins/real-estate::lessee.form.type'),
                'label_attr' => ['class' => 'control-label required'],
                'wrapper'    => [
                    'class' => 'form-group col-md-6',
                ],
                'attr'       => [
                    'class' => 'form-control select-full',
                ],
                'choices'    => [null => trans('plugins/real-estate::lessee.form.select_type')] + LesseeTypeEnum::labels(),
            ])

            ->add('contact_name', 'text', [
                'label'      => trans('plugins/real-estate::lessee.form.contact_name'),
                'label_attr' => ['class' => 'control-label'],
                'wrapper'    => [
                    'class' => 'form-group col-md-6',
                ],
                'attr'       => [
                    'placeholder'  => trans('plugins/real-estate::lessee.form.contact_name'),
                ],
            ])

            ->add('rowClose3', 'html', [
                'html' => '</div>',
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
