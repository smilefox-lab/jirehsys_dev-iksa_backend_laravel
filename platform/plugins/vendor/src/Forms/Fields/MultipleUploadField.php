<?php

namespace Botble\Vendor\Forms\Fields;

use Assets;
use Kris\LaravelFormBuilder\Fields\FormField;

class MultipleUploadField extends FormField
{

    /**
     * @return string
     */
    protected function getTemplate()
    {
        Assets::addScriptsDirectly('https://cdnjs.cloudflare.com/ajax/libs/dropzone/4.3.0/dropzone.js')
            ->addStylesDirectly('https://cdnjs.cloudflare.com/ajax/libs/dropzone/4.3.0/dropzone.css');

        return 'plugins/vendor::forms.fields.multiple-upload';
    }
}
