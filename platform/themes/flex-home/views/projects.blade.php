<section class="main-homes">
    <div class="bgheadproject hidden-xs">
        <div class="description">
            <div class="container-fluid w90">
                <h1 class="text-center">{{ __('Discover our projects') }}</h1>
                <p class="text-center">{{ theme_option('home_project_description') }}</p>
                {!! Theme::partial('breadcrumb') !!}
            </div>
        </div>
    </div>
    <div class="container-fluid w90 padtop30">
            <div class="projecthome">
                <div class="row rowm10">
                    <div class="col-md-3 col-sm-6">
                        {!! Theme::partial('search-box', ['type' => 'project', 'categories' => $categories]) !!}
                    </div>
                    <div class="col-md-9 col-sm-6">
                        @if ($projects->count())
                            <div class="row">
                                @foreach ($projects as $project)
                                    <div class="col-6 col-sm-6 col-md-4 colm10">
                                        <div class="item">
                                            <div class="blii">
                                                <div class="img"><img class="thumb" data-src="{{ get_object_image($project->image, 'small') }}" src="{{ get_object_image($project->image, 'small') }}" alt="{{ $project->name }}">
                                                </div>
                                                <a href="{{ $project->url }}" class="linkdetail"></a>
                                            </div>

                                            <div class="description">
                                                {{--<a href="#" class="text-orange heart" data-toggle="tooltip" data-placement="top" title="" data-original-title="{{ __('I care about this project!!!') }}"><i class="
                                            far
                                             fa-heart"></i></a>--}}
                                                <a href="{{ $project->url }}"><h5>{{ $project->name }}</h5>
                                                    <p class="dia_chi"><i class="fas fa-map-marker-alt"></i> {{ $project->location }}</p>
                                                    @if ($project->price_from || $project->price_to)
                                                        <p class="bold500">{{ __('Price') }}: <span class="from">{{ __('From') }}</span> @if ($project->price_from) <span class="from">{{ __('From') }}</span> {{ format_price($project->price_from, $project->currency, false)  }} @endif @if ($project->price_to) - {{ format_price($project->price_to, $project->currency) }} @endif</p>
                                                    @endif
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="item">{{ __('0 results') }}</p>
                        @endif
                    </div>
                </div>
            </div>
    </div>
</section>
<br>
<div class="col-sm-12">
    <nav class="d-flex justify-content-center pt-3" aria-label="Page navigation example">
        {!! $projects->links() !!}
    </nav>
</div>
<br>
<br>
