<div class="bgheadproject hidden-xs">
    <div class="description">
        <div class="container-fluid w90">
            <h1 class="text-center">{{ __('Contact') }}</h1>
            {!! Theme::partial('breadcrumb') !!}
        </div>
    </div>
</div>
<div class="container padtop50">
    <div class="row">
        <div class="col-sm-12">
            <div class="scontent" id="contact">

                <div class="row">
                        <div class="col-md-6">
                            <div class="wrapper"><h2 class="h2">{{ __('Contact information') }}</h2>
                                <div class="contact-main">
                                    <p>{{ theme_option('about-us') }}</p>
                                    <div class="contact-name" style="text-transform: uppercase">{{ theme_option('company_name') }}</div>
                                    <div class="contact-address">{{ __('Address') }}: {{ theme_option('address') }}
                                    </div>
                                    <div class="contact-phone">{{ __('Hotline') }}: {{ theme_option('hotline') }}</div>
                                    <div class="contact-email">{{ __('Email') }}: {{ theme_option('email') }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <form action="{{ route('public.send.contact') }}" method="post" class="generic-form">
                                <div class="wrapper">
                                    <h2 class="h2">{{ __('HOW WE CAN HELP YOU?') }}</h2>
                                    @csrf
                                    <div class="form-group">
                                        <input class="form-control" type="text" name="name" placeholder="{{ __('Name') }} *"
                                               required="">
                                    </div>
                                    <div class="form-group">
                                        <input class="form-control" type="text" name="email"
                                               placeholder="{{ __('Email') }} *" required="">
                                    </div>
                                    <div class="form-group">
                                        <input class="form-control" type="text" name="phone"
                                               placeholder="{{ __('Phone') }}">
                                    </div>
                                    <div class="form-group">
                                    <textarea class="form-control" name="content" rows="6" minlength="10"
                                      placeholder="{{ __('Message') }} *" required=""></textarea>
                                    </div>
                                    @if (setting('enable_captcha') && is_plugin_active('captcha'))
                                        <div class="form-group">
                                            <label for="contact_robot" class="control-label required">{{ trans('plugins/contact::contact.confirm_not_robot') }}</label>
                                            {!! Captcha::display([], ['lang' => app()->getLocale()]) !!}
                                        </div>
                                    @endif
                                    <div class="alert alert-success text-success text-left" style="display: none;">
                                        <span></span>
                                    </div>
                                    <div class="alert alert-danger text-danger text-left" style="display: none;">
                                        <span></span>
                                    </div>
                                    <br>
                                    <div class="form-actions">
                                        <button class="btn-special" type="submit">{{ __('Send message') }}</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <br><br>
                    <h3>{{ __('Directions') }}</h3>
                    <div class="mapouter">
                        <div class="gmap_canvas">
                            <iframe id="gmap_canvas" width="100%" height="500"
                                    src="https://maps.google.com/maps?q={{ urlencode(theme_option('address')) }}%20&t=&z=13&ie=UTF8&iwloc=&output=embed"
                                    frameborder="0" scrolling="no" marginheight="0" marginwidth="0"></iframe>
                        </div>
                    </div>
                <br>
            </div>
        </div>
    </div>
</div>
<br>
<br>
