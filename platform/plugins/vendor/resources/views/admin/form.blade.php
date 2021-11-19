@extends('core/base::forms.form')
@section('form_end')
    @if ($form->getModel()->id)
        {!! Form::modalAction('add-credit-modal', __('Add credit to account'), 'info', view('plugins/vendor::admin.credit-form', ['account' => $form->getModel()])->render(), 'confirm-add-credit-button', __('Add'), 'modal-md') !!}
    @endif
@endsection
