<?php

namespace Botble\RealEstate\Forms;

use Assets;
use Botble\Base\Forms\FormAbstract;
use Botble\Location\Repositories\Interfaces\CityInterface;
use Botble\RealEstate\Repositories\Interfaces\CurrencyInterface;
use Botble\RealEstate\Repositories\Interfaces\FeatureInterface;
use Botble\RealEstate\Repositories\Interfaces\InvestorInterface;
use Botble\RealEstate\Enums\ProjectStatusEnum;
use Botble\RealEstate\Forms\Fields\LocationField;
use Botble\RealEstate\Http\Requests\ProjectRequest;
use Botble\RealEstate\Models\Project;
use Botble\RealEstate\Repositories\Interfaces\CategoryInterface;
use Throwable;

class ProjectForm extends FormAbstract
{

    /**
     * @var InvestorInterface
     */
    protected $investorRepository;

    /**
     * @var FeatureInterface
     */
    protected $featureRepository;

    /**
     * @var CurrencyInterface
     */
    protected $currencyRepository;

    /**
     * @var CityInterface
     */
    protected $cityRepository;

    /**
     * @var CategoryInterface
     */
    protected $categoryRepository;

    /**
     * ProjectForm constructor.
     * @param InvestorInterface $investorRepository
     * @param FeatureInterface $featureRepository
     * @param CurrencyInterface $currencyRepository
     * @param CityInterface $cityRepository
     * @param CategoryInterface $categoryRepository
     */
    public function __construct(
        InvestorInterface $investorRepository,
        FeatureInterface $featureRepository,
        CurrencyInterface $currencyRepository,
        CityInterface $cityRepository,
        CategoryInterface $categoryRepository
    ) {
        parent::__construct();
        $this->investorRepository = $investorRepository;
        $this->featureRepository = $featureRepository;
        $this->currencyRepository = $currencyRepository;
        $this->cityRepository = $cityRepository;
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * @return mixed|void
     * @throws Throwable
     */
    public function buildForm()
    {
        Assets::addStyles(['datetimepicker'])
            ->addScriptsDirectly('vendor/core/plugins/real-estate/js/real-estate.js')
            ->addStylesDirectly('vendor/core/plugins/real-estate/css/real-estate.css');

        $investors = $this->investorRepository->pluck('re_investors.name', 're_investors.id');

        $currencies = $this->currencyRepository->pluck('currencies.title', 'currencies.id');
        $cities = $this->cityRepository->pluck('cities.name', 'cities.id');
        $categories = $this->categoryRepository->pluck('re_categories.name', 're_categories.id');

        $selectedFeatures = [];
        if ($this->getModel()) {
            $selectedFeatures = $this->getModel()->features()->pluck('id')->all();
        }

        $features = $this->featureRepository->allBy([], [], ['re_features.id', 're_features.name']);

        $this
            ->setupModel(new Project)
            ->setValidatorClass(ProjectRequest::class)
            ->withCustomFields()
            ->addCustomField('location', LocationField::class)
            ->add('name', 'text', [
                'label'      => trans('plugins/real-estate::project.form.name'),
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'placeholder'  => trans('plugins/real-estate::project.form.name'),
                    'data-counter' => 120,
                ],
            ])
            ->add('is_featured', 'onOff', [
                'label'         => trans('core/base::forms.is_featured'),
                'label_attr'    => ['class' => 'control-label'],
                'default_value' => false,
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
            ->add('content', 'editor', [
                'label'      => trans('core/base::forms.content'),
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'rows' => 4,
                ],
            ])
            ->add('city_id', 'customSelect', [
                'label'      => trans('plugins/real-estate::project.form.city'),
                'label_attr' => ['class' => 'control-label'],
                'attr'       => [
                    'class' => 'form-control select-search-full',
                ],
                'choices'    => $cities,
            ])
            ->add('location', 'location', [
                'label'      => trans('plugins/real-estate::project.form.location'),
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'placeholder'  => trans('plugins/real-estate::project.form.location'),
                    'data-counter' => 300,
                ],
            ])
            ->add('rowOpen1', 'html', [
                'html' => '<div class="row">',
            ])
            ->add('number_block', 'number', [
                'label'      => trans('plugins/real-estate::project.form.number_block'),
                'label_attr' => ['class' => 'control-label'],
                'wrapper'    => [
                    'class' => 'form-group col-md-4',
                ],
                'attr'       => [
                    'placeholder' => trans('plugins/real-estate::project.form.number_block'),
                ],
            ])
            ->add('number_floor', 'number', [
                'label'      => trans('plugins/real-estate::project.form.number_floor'),
                'label_attr' => ['class' => 'control-label'],
                'wrapper'    => [
                    'class' => 'form-group col-md-4',
                ],
                'attr'       => [
                    'placeholder' => trans('plugins/real-estate::project.form.number_floor'),
                ],
            ])
            ->add('number_flat', 'number', [
                'label'      => trans('plugins/real-estate::project.form.number_flat'),
                'label_attr' => ['class' => 'control-label'],
                'wrapper'    => [
                    'class' => 'form-group col-md-4',
                ],
                'attr'       => [
                    'placeholder' => trans('plugins/real-estate::project.form.number_flat'),
                ],
            ])
            ->add('rowClose1', 'html', [
                'html' => '</div>',
            ])
            ->add('rowOpen2', 'html', [
                'html' => '<div class="row">',
            ])
            ->add('price_from', 'text', [
                'label'      => trans('plugins/real-estate::project.form.price_from'),
                'label_attr' => ['class' => 'control-label'],
                'wrapper'    => [
                    'class' => 'form-group col-md-4',
                ],
                'attr'       => [
                    'placeholder' => trans('plugins/real-estate::project.form.price_from'),
                    'class'       => 'form-control input-mask-number',
                ],
            ])
            ->add('price_to', 'text', [
                'label'      => trans('plugins/real-estate::project.form.price_to'),
                'label_attr' => ['class' => 'control-label'],
                'wrapper'    => [
                    'class' => 'form-group col-md-4',
                ],
                'attr'       => [
                    'placeholder' => trans('plugins/real-estate::project.form.price_to'),
                    'class'       => 'form-control input-mask-number',
                ],
            ])
            ->add('currency_id', 'customSelect', [
                'label'      => trans('plugins/real-estate::project.form.currency'),
                'label_attr' => ['class' => 'control-label'],
                'wrapper'    => [
                    'class' => 'form-group col-md-4',
                ],
                'attr'       => [
                    'class' => 'form-control select-full',
                ],
                'choices'    => $currencies,
            ])
            ->add('rowClose2', 'html', [
                'html' => '</div>',
            ])
            ->addMetaBoxes([
                'image'    => [
                    'title'    => trans('plugins/real-estate::project.form.images'),
                    'content'  => view('plugins/real-estate::partials.form-images',
                        ['object' => $this->getModel()])->render(),
                    'priority' => 0,
                ],
                'features' => [
                    'title'    => trans('plugins/real-estate::property.form.features'),
                    'content'  => view('plugins/real-estate::partials.form-features',
                        compact('selectedFeatures', 'features'))->render(),
                    'priority' => 1,
                ],
            ])
            ->add('status', 'customSelect', [
                'label'      => trans('core/base::tables.status'),
                'label_attr' => ['class' => 'control-label required'],
                'choices'    => ProjectStatusEnum::labels(),
            ])
            ->add('category_id', 'customSelect', [
                'label'      => trans('plugins/real-estate::project.form.category'),
                'label_attr' => ['class' => 'control-label'],
                'attr'       => [
                    'class' => 'form-control select-search-full',
                ],
                'choices'    => $categories,
            ])
            ->add('investor_id', 'customSelect', [
                'label'      => trans('plugins/real-estate::project.form.investor'),
                'label_attr' => ['class' => 'control-label'],
                'attr'       => [
                    'class' => 'form-control select-search-full',
                ],
                'choices'    => [0 => __('Select an investor...')] + $investors,
            ])
            ->add('date_finish', 'text', [
                'label'      => trans('plugins/real-estate::project.form.date_finish'),
                'label_attr' => ['class' => 'control-label'],
                'attr'       => [
                    'placeholder'      => trans('plugins/real-estate::project.form.date_finish'),
                    'class'            => 'form-control datepicker',
                    'data-date-format' => config('core.base.general.date_format.js.date'),
                ],
            ])
            ->add('date_sell', 'text', [
                'label'      => trans('plugins/real-estate::project.form.date_sell'),
                'label_attr' => ['class' => 'control-label'],
                'attr'       => [
                    'placeholder'      => trans('plugins/real-estate::project.form.date_sell'),
                    'class'            => 'form-control datepicker',
                    'data-date-format' => config('core.base.general.date_format.js.date'),
                ],
            ])
            ->setBreakFieldPoint('status');
    }
}
