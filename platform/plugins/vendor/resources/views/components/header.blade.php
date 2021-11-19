<nav class="navbar navbar-expand-md navbar-light bg-white bb b--black-10">
  <div class="container">

        @if (theme_option('logo'))
          <a href="{{ url('/') }}"><img src="{{ get_image_url(theme_option('logo')) }}" alt="{{ theme_option('site_title') }}" height="35"></a>
        @else
          <div class="brand-container tc mr2 br2">
            <a class="navbar-brand b white ma0 pa0 dib w-100" href="{{ url('/') }}" title="{{ theme_option('site_title') }}">{{ ucfirst(mb_substr(theme_option('site_title'), 0, 1, 'utf-8')) }}</a>
          </div>
        @endif

    <button class="navbar-toggler" type="button" data-toggle="collapse"
            data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
            aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <!-- Right Side Of Navbar -->
      <ul class="navbar-nav ml-auto my-2">
        <!-- Authentication Links -->
        @if (!auth()->guard('vendor')->check())
          <li>
            <a class="no-underline mr2 black-50 hover-black-70 pv1 ph2 db" style="text-decoration: none; line-height: 32px;" href="{{ route('public.vendor.login') }}">
                <i class="fas fa-sign-in-alt"></i> {{ trans('plugins/vendor::dashboard.login-cta') }}
            </a>
          </li>
          <li>
            <a class="no-underline mr2 black-50 hover-black-70 pv1 ph2 db" style="text-decoration: none; line-height: 32px;" href="{{ route('public.vendor.register') }}">
                <i class="fas fa-user-plus"></i> {{ trans('plugins/vendor::dashboard.register-cta') }}
            </a>
          </li>
        @else
          <li>
            <a class="no-underline mr2 black-50 hover-black-70 pv1 ph2 db mr2" style="text-decoration: none; line-height: 32px;" href="{{ route('public.vendor.dashboard') }}" title="{{ trans('plugins/vendor::dashboard.header_profile_link') }}">
              <span>
                <img src="{{ auth()->guard('vendor')->user()->avatar_url }}" class="br-100 v-mid mr1" style="width: 30px;">
                <span>{{ auth()->guard('vendor')->user()->getFullName() }}</span>
              </span>
            </a>
          </li>
          <li>
              <a class="no-underline mr2 black-50 hover-black-70 pv1 ph2 db mr2" style="text-decoration: none; line-height: 32px;" href="{{ route('public.vendor.packages') }}" title="{{ trans('plugins/vendor::vendor.credits') }}">
                  <i class="far fa-credit-card mr1"></i>{{ trans('plugins/vendor::vendor.buy_credits') }} <span class="badge badge-info">{{ auth()->guard('vendor')->user()->credits }} {{ trans('plugins/vendor::vendor.credits') }}</span>
              </a>
          </li>
          {!! apply_filters(VENDOR_TOP_MENU_FILTER, null) !!}
              <li>
                  <a class="no-underline mr2 black-50 hover-black-70 pv1 ph2 db mr2" style="text-decoration: none; line-height: 32px;" href="{{ route('public.vendor.properties.index') }}" title="{{ trans('plugins/vendor::property.properties') }}">
                      <i class="far fa-newspaper mr1"></i>{{ trans('plugins/vendor::property.properties') }}
                  </a>
              </li>
          @if (auth('vendor')->user()->canPost())
              <li>
                  <a class="no-underline mr2 black-50 hover-black-70 pv1 ph2 db mr2" style="text-decoration: none; line-height: 32px;" href="{{ route('public.vendor.properties.create') }}" title="{{ trans('plugins/vendor::property.write_property') }}">
                      <i class="far fa-edit mr1"></i>{{ trans('plugins/vendor::property.write_property') }}
                  </a>
              </li>
          @endif
          <li>
            <a class="no-underline mr2 black-50 hover-black-70 pv1 ph2 db" style="text-decoration: none; line-height: 32px;" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" title="{{ trans('plugins/vendor::dashboard.header_logout_link') }}">
              <i class="fas fa-sign-out-alt mr1"></i><span class="dn-ns">{{ trans('plugins/vendor::dashboard.header_logout_link') }}</span>
            </a>
            <form id="logout-form" action="{{ route('public.vendor.logout') }}" method="POST" style="display: none;">
              @csrf
            </form>
          </li>
        @endguest
      </ul>
    </div>
  </div>
</nav>
