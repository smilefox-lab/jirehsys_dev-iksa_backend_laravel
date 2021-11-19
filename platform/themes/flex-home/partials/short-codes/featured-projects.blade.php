@php
    use Botble\RealEstate\Enums\ProjectStatusEnum;
    use Botble\RealEstate\Repositories\Interfaces\ProjectInterface;

    $projects = collect([]);

    if (is_plugin_active('real-estate')) {
        $projects = app(ProjectInterface::class)->advancedGet([
            'condition' => [
                're_projects.is_featured' => true,
                're_projects.status'      => ProjectStatusEnum::SELLING,
            ],
            'take'      => theme_option('number_of_featured_projects', 4),
            'with'      => ['currency'],
        ]);
     }
@endphp
@if ($projects->count())
    <div class="box_shadow" style="margin-top: 0;">
        <div class="container-fluid w90">
            <div class="projecthome">
                <div class="row">
                    <div class="col-12">
                        <h2>{{ __('Featured projects') }}</h2>
                        <p style="margin: 0; margin-bottom: 10px">{{ theme_option('home_project_description') }}</p>
                    </div>
                </div>
                <div class="row rowm10">
                    @foreach ($projects as $project)
                        <div class="col-6 col-sm-4  col-md-3 colm10">
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
                                            <p class="bold500">{{ __('Price') }}: @if ($project->price_from) <span class="from">{{ __('From') }}</span> {{ format_price($project->price_from, $project->currency, false)  }} @endif @if ($project->price_to) - {{ format_price($project->price_to, $project->currency) }} @endif</p>
                                        @endif
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endif
