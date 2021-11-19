<?php

namespace Botble\Location\Forms\Fields;

use Assets;
use Kris\LaravelFormBuilder\Fields\SelectType;

class CityField extends SelectType
{

    /**
     * {@inheritDoc}
     */
    protected $valueProperty = 'selected';

    /**
     * {@inheritDoc}
     */
    protected function getTemplate()
    {
        Assets::addScriptsDirectly(['vendor/core/plugins/location/js/location.js']);

        return 'plugins/location::forms.city-field';
    }
}
