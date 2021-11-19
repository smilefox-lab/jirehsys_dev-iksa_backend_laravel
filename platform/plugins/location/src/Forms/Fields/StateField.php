<?php

namespace Botble\Location\Forms\Fields;

use Assets;
use Kris\LaravelFormBuilder\Fields\SelectType;

class StateField extends SelectType
{

    /**
     * {@inheritDoc}
     */
    protected function getTemplate()
    {
        Assets::addScriptsDirectly(['vendor/core/plugins/location/js/location.js']);

        return 'plugins/location::forms.state-field';
    }
}
