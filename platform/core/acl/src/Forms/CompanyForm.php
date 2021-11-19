<?php

namespace Botble\ACL\Forms;

use Botble\Base\Forms\FormAbstract;
use Botble\ACL\Enums\CompanyStatusEnum;
use Botble\ACL\Http\Requests\CompanyRequest;
use Botble\ACL\Models\Company;

class CompanyForm extends FormAbstract
{

    /**
     * @return mixed|void
     * @throws \Throwable
     */
    public function buildForm()
    {
        $this
            ->setupModel(new Company)
            ->setValidatorClass(CompanyRequest::class)
            ->withCustomFields()
            ->add('rowOpen1', 'html', [
                'html' => '<div class="row">',
            ])

            ->add('name', 'text', [
                'label' => trans('core/acl::company.form.name'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'placeholder'  => trans('core/acl::company.form.name_placeholder'),
                    'data-counter' => 255,
                ],
                'wrapper'    => [
                    'class' => 'form-group col-md-6',
                ],
            ])

            ->add('rut', 'text', [
                'label' => trans('core/acl::company.form.rut'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'placeholder'  => trans('core/acl::company.form.rut_placeholder'),
                ],
                'wrapper'    => [
                    'class' => 'form-group col-md-6',
                ],
            ])

            ->add('rowClose1', 'html', [
                'html' => '</div>',
            ])

            ->add('rowOpen2', 'html', [
                'html' => '<div class="row">',
            ])

            ->add('address', 'textarea', [
                'label'      => trans('core/acl::company.form.address'),
                'label_attr' => ['class' => 'control-label'],
                'attr'       => [
                    'rows'         => 3,
                    'placeholder'  => trans('core/acl::company.form.address_placeholder'),
                    'data-counter' => 255,
                ],
                'wrapper'    => [
                    'class' => 'form-group col-md-6',
                ],
            ])

            ->add('phone', 'text', [
                'label' => trans('core/acl::company.form.phone'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'placeholder'  => trans('core/acl::company.form.phone_placeholder'),
                ],
                'wrapper'    => [
                    'class' => 'form-group col-md-6',
                ],
            ])

            ->add('rowClose2', 'html', [
                'html' => '</div>',
            ])

            ->add('file', 'file', [
                'label'      => false,
                'attr'       => [
                    'multiple'     => true,
                    'style'        => 'display: none'
                ]
            ])

            ->addMetaBoxes([
                'file' => [
                    'title'    => trans('core/acl::company.form.files'),
                    'content'  => view('core/acl::partials.form-files',
                        ['files' => $this->getModel()->files])->render(),
                    'priority' => 1,
                ],
            ])

            ->add('status', 'select', [
                'label'      => trans('core/base::tables.status'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'class' => 'form-control select-full',
                ],
                'choices'    => CompanyStatusEnum::labels(),
            ])
            ->setBreakFieldPoint('status');
    }
}
