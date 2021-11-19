<div class="bgheadproject hidden-xs">
    <div class="description">
        <div class="container-fluid w90">
            <h1 class="text-center">{{ __('Careers') }}</h1>
            {!! Theme::partial('breadcrumb') !!}
        </div>
    </div>
</div>
<div class="container padtop50">
    <div class="row">
        <div class="col-sm-9">
            <div class="job-list">
                @foreach ($careers as $career)
                    <div class="job-item">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="job-title">
                                    <a href="{{ route('public.career', $career->slug) }}">{{ $career->name }}</a></div>
                                <div class="job-body"><p>{{ $career->location }}</p>
                                    <p>{{ __('Salary') }}: {{ $career->salary }}</p>
                                    <p>{{ __('Posted at') }}: {{ $career->created_at->format('Y-m-d') }}</p></div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="col-sm-3">
            {!! dynamic_sidebar('primary_sidebar') !!}
        </div>
    </div>
</div>
<br>
<br>
