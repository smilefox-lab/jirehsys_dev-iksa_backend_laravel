<?php

namespace Botble\RealEstate\Forms\Fields;

use Assets;
use Kris\LaravelFormBuilder\Fields\SelectType;

class PropertyField extends SelectType
{
    /**
     * {@inheritDoc}
     */
    protected function getTemplate()
    {
        Assets::addScriptsDirectly(['vendor/core/plugins/real-estate/js/property.js']);

        return 'plugins/real-estate::forms.fields.property';
    }
}
