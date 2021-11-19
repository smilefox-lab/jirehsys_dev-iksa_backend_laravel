@extends('core/base::layouts.master')
@section('content')
    {!! Form::open(['url' => route('real-estate.settings'), 'class' => 'main-setting-form']) !!}

        @if (!app()->environment('demo'))
            <div class="flexbox-annotated-section">
                <div class="flexbox-annotated-section-annotation">
                    <div class="annotated-section-title pd-all-20">
                        <h2>{{ trans('plugins/real-estate::real-estate.google_map') }}</h2>
                    </div>
                    <div class="annotated-section-description pd-all-20 p-none-t">
                        <p class="color-note">{{ trans('plugins/real-estate::real-estate.google_map_description') }}</p>
                    </div>
                </div>
                <div class="flexbox-annotated-section-content">
                    <div class="wrapper-content pd-all-20">
                        <div class="form-group">
                            <label class="text-title-field" for="google_map_api_key">{{ trans('plugins/real-estate::real-estate.api_key') }}</label>
                            <input type="text" class="form-control" name="google_map_api_key" value="{{ setting('google_map_api_key') }}" id="google_map_api_key" placeholder="AIzaSyAvS1cTtst2cOnxxxxxxxxxxxxx">
                            <span class="help-ts">{{ trans('plugins/real-estate::real-estate.api_key_helper') }} (<a href="https://console.developers.google.com/apis/dashboard" target="_blank">https://console.developers.google.com/apis/dashboard</a>)</span>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="flexbox-annotated-section" style="border: none">
            <div class="flexbox-annotated-section-annotation">
                &nbsp;
            </div>
            <div class="flexbox-annotated-section-content">
                <button class="btn btn-primary" type="submit">{{ trans('plugins/real-estate::currency.save_settings') }}</button>
            </div>
        </div>
    </div>
    {!! Form::close() !!}
@endsection
