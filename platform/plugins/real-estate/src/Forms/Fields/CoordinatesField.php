<?php

namespace Botble\RealEstate\Forms\Fields;

use Kris\LaravelFormBuilder\Fields\FormField;

class CoordinatesField extends FormField
{
    /**
     * Get the template, can be config variable or view path.
     *
     * @return string
     */
    protected function getTemplate()
    {
        return 'plugins/real-estate::forms.fields.coordinates';
    }
}
