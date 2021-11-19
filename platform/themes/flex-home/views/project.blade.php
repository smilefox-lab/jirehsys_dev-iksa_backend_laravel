<main class="detailproject" style="background: #FFF;">
    <div class="boxsliderdetail">
        <div class="slidetop">
            <div class="owl-carousel" id="listcarousel">
                @foreach ($project->images as $image)
                    <div class="item"><img  src="{{ get_object_image($image) }}" class="showfullimg" rel="{{ $loop->index }}" alt="{{ $project->name }}"></div>
                @endforeach
            </div>
        </div>
        <div class="slidebot">
            <div style="max-width: 800px; margin: 0 auto;">
                    <div class="owl-carousel" id="listcarouselthumb">
                        @foreach ($project->images as $image)
                            <div class="item cthumb" rel="{{ $loop->index }}"><img  src="{{ get_object_image($image) }}" class="showfullimg" rel="{{ $loop->index }}" alt="{{ $project->name }}"></div>
                        @endforeach
                    </div>
                    <i class="fas fa-chevron-right ar-next"></i>
                    <i class="fas fa-chevron-left ar-prev"></i>
            </div>
        </div>
    </div>
    <div id="gallery" data-images="{{ json_encode($images) }}"></div>

    <div class="container-fluid bgmenupro">
        <div class="container-fluid w90 padtop30" style="padding: 15px 0;">
            <div class="col-12">
                <h1 class="title" style="font-size: 1.5rem; font-weight: bold; margin-bottom: 0;">{{ $project->name }}</h1>
                <p class="addresshouse"><i class="fas fa-map-marker-alt"></i>  {{ $project->location }}</p>
            </div>
        </div>
    </div>

    <div class="container-fluid w90 padtop30 single-post">
        <section class="general">
            <div class="row">
                <div class="col-md-8">
                    <div class="head">{{ __('Overview') }}</div>
                    <span class="line_title"></span>
                    <div class="row">
                        <div class="col-sm-6 lineheight220">
                            <div><span>{{ __('Status') }}:</span> <b>{{ $project->status->label() }}</b></div>
                            <div><span>{{ __('Category') }}:</span> <b>{{ $project->category->name }}</b></div>
                            <div><span>{{ __('Investor') }}:</span> <b>{{ $project->investor->name }}</b></div>
                            @if ($project->price_from || $project->price_to)
                            <div><span>{{ __('Price') }}:</span> <b>@if ($project->price_from) <span class="from">{{ __('From') }}</span> {{ format_price($project->price_from, $project->currency, false)  }} @endif @if ($project->price_to) - {{ format_price($project->price_to, $project->currency) }} @endif</b></div>
                            @endif
                        </div>
                        <div class="col-sm-6 lineheight220">
                            <div><span>{{ __('Number of blocks') }}:</span> <b>{{ $project->number_block }}</b></div>
                            <div><span>{{ __('Number of floors') }}:</span> <b>{{ $project->number_floor }}</b>	</div>
                            <div><span>{{ __('Number of flats') }}:</span> <b>{{ $project->number_flat }}</b></div>
                        </div>
                    </div>

                    <div class="head">{{ __('Description') }}</div>
                    @if ($project->content)
                        {!! $project->content !!}
                    @else
                        <p>{{ __('Updating...') }}</p>
                    @endif
                    @if ($project->features->count())
                        <div class="head">{{ __('Features') }}</div>
                        <div class="row">
                            @foreach($project->features as $feature)
                                <div class="col-sm-4">
                                    <p><i class="fas fa-check text-orange text0i"></i>  {{ $feature->name }}</p>
                                </div>
                            @endforeach
                        </div>
                    @endif
                    <br>
                    <div class="mapouter">
                        <div class="gmap_canvas">
                            <iframe id="gmap_canvas" width="100%" height="500"
                                    src="https://maps.google.com/maps?q={{ urlencode($project->location) }}%20&t=&z=13&ie=UTF8&iwloc=&output=embed"
                                    frameborder="0" scrolling="no" marginheight="0" marginwidth="0"></iframe>
                        </div>
                    </div>
                    <br>
                    <br>
                    {!! Theme::partial('share', ['title' => __('Share this project'), 'description' => $project->description]) !!}
                    <div class="clearfix"></div>
                    <br>
                </div>
                <div class="col-md-4 padtop10">
                    <div class="boxright">
                        {!! Theme::partial('consult-form', ['type' => 'project', 'data' => $project]) !!}
                    </div>
                </div>
            </div>

            <h5  class="headifhouse">{{ __('Properties For Sale') }}</h5>
            <property-component type="project-properties-for-sell" project_id="{{ $project->id }}" url="{{ route('public.ajax.properties') }}" :show_empty_string="true"></property-component>
            <br>
            <br>
            <h5  class="headifhouse">{{ __('Properties For Rent') }}</h5>
            <property-component type="project-properties-for-rent" project_id="{{ $project->id }}" url="{{ route('public.ajax.properties') }}" :show_empty_string="true"></property-component>
            <br>
            <br>
            <br>
            <br>
            <br>
        </section>

    </div>
</main>
<br>
<br>
