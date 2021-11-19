@extends('core/base::layouts.base')

@section('body-class') login @stop

@section ('page')
    <div class="container-fluid">
        <div class="row auth-container">

            <div class="login-sidebar">

                <div class="login-container">

                    <img src="{{ url(config('core.acl.general.logo')) }}" alt="">
                    @yield('content')

                    @if (setting('enable_multi_language_in_admin') != false && count(Assets::getAdminLocales()) > 1)
                        <p> {{ trans('core/acl::auth.languages') }}:
                            @foreach (Assets::getAdminLocales() as $key => $value)
                                <span @if (app()->getLocale() == $key) class="active" @endif>
                                    <a href="{{ route('settings.language', $key) }}">
                                        <span>{{ $value['name'] }}</span>
                                    </a>
                                </span>
                            @endforeach
                        </p>
                    @endif
                </div> <!-- .login-container -->

            </div> <!-- .login-sidebar -->
        </div> <!-- .row -->
    </div>
@stop
