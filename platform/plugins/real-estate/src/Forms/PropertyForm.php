<?php

namespace Botble\RealEstate\Forms;

use Assets;
use Botble\ACL\Repositories\Interfaces\CompanyInterface;
use Botble\Base\Forms\FormAbstract;
use Botble\Location\Repositories\Interfaces\CommuneInterface;
use Botble\RealEstate\Enums\PropertyStatusEnum;
use Botble\RealEstate\Forms\Fields\CoordinatesField;
use Botble\RealEstate\Forms\Fields\LocationField;
use Botble\RealEstate\Http\Requests\PropertyRequest;
use Botble\RealEstate\Models\Property;
use Botble\RealEstate\Repositories\Interfaces\FeatureInterface;
use Botble\RealEstate\Repositories\Interfaces\LesseeInterface;
use Botble\RealEstate\Repositories\Interfaces\PropertyInterface;
use Botble\RealEstate\Repositories\Interfaces\TypeInterface;
use Throwable;

class PropertyForm extends FormAbstract
{

    /**
     * @var PropertyInterface
     */
    protected $propertyRepository;

    /**
     * @var FeatureInterface
     */
    protected $featureRepository;

    /**
     * @var CommuneInterface
     */
    protected $communeRepository;

    /**
     * @var CompanyInterface
     */
    protected $companyRepository;

    /**
     * @var TypeInterface
     */
    protected $typeRepository;

    /**
     * ProjectForm constructor.
     * @param PropertyInterface $propertyRepository
     * @param FeatureInterface $featureRepository
     * @param CommuneInterface $communeRepository
     * @param CompanyInterface $companyRepository
     * @param TypeInterface $typeRepository
     */
    public function __construct(
        PropertyInterface $propertyRepository,
        FeatureInterface $featureRepository,
        CommuneInterface $communeRepository,
        CompanyInterface $companyRepository,
        TypeInterface $typeRepository
    ) {
        parent::__construct();
        $this->propertyRepository = $propertyRepository;
        $this->featureRepository = $featureRepository;
        $this->communeRepository = $communeRepository;
        $this->companyRepository = $companyRepository;
        $this->typeRepository = $typeRepository;
    }

    /**
     * @return mixed|void
     * @throws Throwable
     */
    public function buildForm()
    {
        Assets::addStyles(['datetimepicker'])
            ->addScripts(['input-mask'])
            ->addStylesDirectly('vendor/core/plugins/real-estate/css/real-estate.css');

        $communes = $this->communeRepository->pluck('communes.name', 'communes.id');

        $companies = $this->companyRepository
                        ->getUserCompany()
                        ->pluck('name', 'id')
                        ->toArray();
        $types = $this->typeRepository->pluck('re_types.name', 're_types.id');

        $selectedFeatures = [];

        if ($this->getModel()) {
            $selectedFeatures = $this->getModel()->features()->pluck('re_features.id')->all();
        }

        $features = $this->featureRepository->allBy([], [], ['re_features.id', 're_features.name']);

        $this
            ->setupModel(new Property)
            ->setValidatorClass(PropertyRequest::class)
            ->withCustomFields()
            ->addCustomField('location', LocationField::class)
            ->addCustomField('coordinates', CoordinatesField::class)

            ->add('rowOpen1', 'html', [
                'html' => '<div class="row">',
            ])
            ->add('name', 'text', [
                'label'      => trans('plugins/real-estate::property.form.name'),
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'placeholder'  => trans('plugins/real-estate::property.form.name'),
                    'data-counter' => 120,
                ],
                'wrapper'    => [
                    'class' => 'form-group col-md-6',
                ],
            ])

            ->add('role', 'text', [
                'label'      => trans('plugins/real-estate::property.form.role'),
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'placeholder'  => trans('plugins/real-estate::property.form.role'),
                    'data-counter' => 120,
                ],
                'wrapper'    => [
                    'class' => 'form-group col-md-6',
                ],
            ])
            ->add('rowClose1', 'html', [
                'html' => '</div>',
            ])
            ->add('description', 'textarea', [
                'label'      => trans('core/base::forms.description'),
                'label_attr' => ['class' => 'control-label'],
                'attr'       => [
                    'rows'         => 4,
                    'placeholder'  => trans('core/base::forms.description_placeholder'),
                    'data-counter' => 350,
                ],
            ])

            ->add('commune_id', 'customSelect', [
                'label'      => trans('plugins/real-estate::property.form.commune'),
                'label_attr' => ['class' => 'control-label'],
                'attr'       => [
                    'class' => 'form-control select-search-full',
                ],
                'choices'    => $communes,
            ])

            ->add('coordinates', 'coordinates', [
                'label'      => trans('plugins/real-estate::property.form.coordinates'),
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'placeholder'  => trans('plugins/real-estate::property.form.coordinates_placeholder'),
                ],
            ])
            ->add('location', 'location', [
                'label'      => trans('plugins/real-estate::property.form.location'),
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'placeholder'  => trans('plugins/real-estate::property.form.location'),
                    'data-counter' => 300,
                ],
            ])
            ->add('rowOpen2', 'html', [
                'html' => '<div class="row">',
            ])
            ->add('leaves', 'text', [
                'label'      => trans('plugins/real-estate::property.form.leaves'),
                'label_attr' => ['class' => 'control-label'],
                'wrapper'    => [
                    'class' => 'form-group col-md-4',
                ],
                'attr'       => [
                    'placeholder' => trans('plugins/real-estate::property.form.leaves'),
                ],
            ])
            ->add('number', 'text', [
                'label'      => trans('plugins/real-estate::property.form.number'),
                'label_attr' => ['class' => 'control-label'],
                'wrapper'    => [
                    'class' => 'form-group col-md-4',
                ],
                'attr'       => [
                    'placeholder' => trans('plugins/real-estate::property.form.number'),
                ],
            ])
            ->add('year', 'text', [
                'label'      => trans('plugins/real-estate::property.form.year'),
                'label_attr' => ['class' => 'control-label'],
                'wrapper'    => [
                    'class' => 'form-group col-md-4',
                ],
                'attr'       => [
                    'placeholder'  => trans('plugins/real-estate::property.form.placeholder_year'),
                    'data-counter' => 4,
                ],
            ])
            ->add('rowClose2', 'html', [
                'html' => '</div>',
            ])

            ->add('rowOpen3', 'html', [
                'html' => '<div class="row">',
            ])
            ->add('buy', 'text', [
                'label'      => trans('plugins/real-estate::property.form.buy'),
                'label_attr' => ['class' => 'control-label'],
                'wrapper'    => [
                    'class' => 'form-group col-md-4',
                ],
                'attr'       => [
                    'id'          => 'buy-number',
                    'placeholder' => trans('plugins/real-estate::property.form.buy'),
                    'class'       => 'form-control input-mask-number',
                ],
            ])
            ->add('date_deed', 'date', [
                'label'      => trans('plugins/real-estate::property.form.date_deed'),
                'label_attr' => ['class' => 'control-label'],
                'wrapper'    => [
                    'class' => 'form-group col-md-4',
                ],
                'attr'       => [
                    'placeholder' => trans('plugins/real-estate::property.form.date_deed'),
                ],
            ])
            ->add('appraisal', 'text', [
                'label'      => trans('plugins/real-estate::property.form.appraisal'),
                'label_attr' => ['class' => 'control-label'],
                'wrapper'    => [
                    'class' => 'form-group col-md-4',
                ],
                'attr'       => [
                    'id'          => 'appraisal-number',
                    'placeholder' => trans('plugins/real-estate::property.form.appraisal'),
                    'class'       => 'form-control input-mask-number',
                ],
            ])
            ->add('rowClose3', 'html', [
                'html' => '</div>',
            ])

            ->add('rowOpen4', 'html', [
                'html' => '<div class="row">',
            ])
            ->add('uf', 'text', [
                'label'      => trans('plugins/real-estate::property.form.uf'),
                'label_attr' => ['class' => 'control-label'],
                'wrapper'    => [
                    'class' => 'form-group col-md-4',
                ],
                'attr'       => [
                    'id'          => 'uf-number',
                    'placeholder' => trans('plugins/real-estate::property.form.uf'),
                    'class'       => 'form-control input-mask-number',
                ],
            ])
            ->add('pesos', 'text', [
                'label'      => trans('plugins/real-estate::property.form.pesos'),
                'label_attr' => ['class' => 'control-label'],
                'wrapper'    => [
                    'class' => 'form-group col-md-4',
                ],
                'attr'       => [
                    'id'          => 'pesos-number',
                    'placeholder' => trans('plugins/real-estate::property.form.pesos'),
                    'class'       => 'form-control input-mask-number',
                ],
            ])

            ->add('profitability', 'text', [
                'label'      => trans('plugins/real-estate::property.form.profitability'),
                'label_attr' => ['class' => 'control-label'],
                'wrapper'    => [
                    'class' => 'form-group col-md-4',
                ],
                'attr'       => [
                    'id'          => 'profitability-number',
                    'placeholder' => trans('plugins/real-estate::property.form.profitability'),
                    'class'       => 'form-control input-mask-number',
                ],
            ])

            ->add('rowClose4', 'html', [
                'html' => '</div>',
            ])

            ->add('rowOpen5', 'html', [
                'html' => '<div class="row">',
            ])

            ->add('square', 'number', [
                'label'      => trans('plugins/real-estate::property.form.square'),
                'label_attr' => ['class' => 'control-label required'],
                'wrapper'    => [
                    'class' => 'form-group col-md-4',
                ],
                'attr'       => [
                    'placeholder' => trans('plugins/real-estate::property.form.square'),
                ],
            ])
            ->add('square_build', 'number', [
                'label'      => trans('plugins/real-estate::property.form.square_build'),
                'label_attr' => ['class' => 'control-label required'],
                'wrapper'    => [
                    'class' => 'form-group col-md-4',
                ],
                'attr'       => [
                    'placeholder' => trans('plugins/real-estate::property.form.square_build'),
                ],
            ])
            ->add('rowClose5', 'html', [
                'html' => '</div>',
            ])

            ->add('image', 'file', [
                'label'      => false,
                'attr'       => [
                    'multiple'     => true,
                    'style'        => 'display: none'
                ]
            ])
            ->add('technical', 'file', [
                'label'      => false,
                'attr'       => [
                    'multiple'     => true,
                    'style'        => 'display: none'
                ]
            ])
            ->add('legal', 'file', [
                'label'      => false,
                'attr'       => [
                    'multiple'     => true,
                    'style'        => 'display: none'
                ]
            ])

            ->add('plane', 'file', [
                'label'      => false,
                'attr'       => [
                    'multiple'     => true,
                    'style'        => 'display: none'
                ]
            ])

            ->addMetaBoxes([
                'image'    => [
                    'title'    => trans('plugins/real-estate::property.form.images'),
                    'content'  => view('plugins/real-estate::partials.form-images',
                        ['images' => $this->getModel()->images])->render(),
                    'attr'     => [
                        'style' => 'position: relative',
                    ],
                    'priority' => 0,
                ],
                'technical' => [
                    'title'    => trans('plugins/real-estate::property.form.technical'),
                    'content'  => view('plugins/real-estate::partials.form-technicals',
                                    [
                                        'technical' => $this->getModel()->files_technical,
                                    ]
                                  )->render(),
                    'attr'     => [
                        'style' => 'position: relative',
                    ],
                    'priority' => 1,
                ],
                'legal' => [
                    'title'    => trans('plugins/real-estate::property.form.legal'),
                    'content'  => view('plugins/real-estate::partials.form-legals',
                                    [
                                        'legal' => $this->getModel()->files_legal,
                                    ]
                                  )->render(),
                    'priority' => 1,
                ],
                'plane' => [
                    'title'    => trans('plugins/real-estate::property.form.plane'),
                    'content'  => view('plugins/real-estate::partials.form-planes',
                                    [
                                        'plane' => $this->getModel()->files_plane,
                                    ]
                                  )->render(),
                    'priority' => 1,
                ],
                'features' => [
                    'title'    => trans('plugins/real-estate::property.form.features'),
                    'content'  => view('plugins/real-estate::partials.form-features',
                        compact('selectedFeatures', 'features'))->render(),
                    'priority' => 2,
                ],
            ])

            ->add('company_id', 'customSelect', [
                'label'      => trans('core/acl::company.form.label'),
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'class' => 'form-control select-search-full',
                ],
                'choices'    => $companies,
            ])
            ->add('type_id', 'customSelect', [
                'label'      => trans('plugins/real-estate::property.form.type'),
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'class'  => 'form-control select-search-full',
                ],
                'choices'    => $types,
            ])

            ->add('status', 'customSelect', [
                'label'      => trans('core/base::tables.status'),
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'class' => 'form-control select-search-full',
                ],
                'choices'    => PropertyStatusEnum::labels(),
            ])

            ->setBreakFieldPoint('company_id');
    }
}
