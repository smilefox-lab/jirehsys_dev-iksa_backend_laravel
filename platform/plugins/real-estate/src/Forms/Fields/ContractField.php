<?php

namespace Botble\RealEstate\Forms\Fields;

use Assets;
use Kris\LaravelFormBuilder\Fields\SelectType;

class ContractField extends SelectType
{
    /**
     * {@inheritDoc}
     */
    protected function getTemplate()
    {
        return 'plugins/real-estate::forms.fields.contract';
    }
}
