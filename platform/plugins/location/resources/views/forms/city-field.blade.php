@if ($showLabel && $showField)
    @if ($options['wrapper'] !== false)
        <div {!! $options['wrapperAttrs'] !!}>
    @endif
@endif

@if ($showLabel && $options['label'] !== false && $options['label_show'])
    {!! Form::customLabel($name, $options['label'], $options['label_attr']) !!}
@endif

@php
    $options['attr']['class'] = Arr::get($options['attr'], 'class', '') . ' form-control select-search-full';
    $options['attr']['data-type'] = 'city';
    $options['attr']['data-origin-value'] = $options['selected'];
    $options['attr']['data-placeholder'] = __('Select state');
    $emptyVal = $options['empty_value'] ? ['' => $options['empty_value']] : null;
@endphp

@if ($showField)
    {!! Form::select($name, (array)$emptyVal + $options['choices'], $options['selected'], $options['attr']) !!}
    @include('core/base::forms.partials.help_block')
@endif

@include('core/base::forms.partials.errors')

@if ($showLabel && $showField)
    @if ($options['wrapper'] !== false)
    </div>
    @endif
@endif
