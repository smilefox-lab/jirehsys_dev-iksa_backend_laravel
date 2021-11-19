<section class="main-homes">
    <div class="bgheadproject hidden-xs">
        <div class="description">
            <div class="container-fluid w90">
                <h1 class="text-center">{{ __('Properties') }}</h1>
                <p class="text-center">{{ theme_option('properties_description') }}</p>
                {!! Theme::partial('breadcrumb') !!}
            </div>
        </div>
    </div>
    <div class="container-fluid w90 padtop30">
        <div class="projecthome">
            <div class="row rowm10">
                <div class="col-md-3 col-sm-6">
                    {!! Theme::partial('search-box', ['type' => 'property', 'categories' => $categories]) !!}
                </div>
                <div class="col-md-9 col-sm-6">
                    @if ($properties->count())
                        <div class="row">
                            @foreach ($properties as $property)
                                <div class="col-6 col-sm-6 col-md-4 colm10">
                                    <div class="item">
                                        <div class="blii">
                                            <div class="img"><img class="thumb" data-src="{{ get_object_image($property->image, 'small') }}" src="{{ get_object_image($property->image, 'small') }}" alt="{{ $property->name }}">
                                            </div>
                                            <a href="{{ $property->url }}" class="linkdetail"></a>
                                            <div class="status">{!! $property->status->toHtml() !!}</div>
                                        </div>

                                        <div class="description">
                                            {{--<a href="#" class="text-orange heart" data-toggle="tooltip" data-placement="top" title="" data-original-title="{{ __('I care about this project!!!') }}"><i class="
                                        far
                                         fa-heart"></i></a>--}}
                                            <a href="{{ $property->url }}"><h5>{{ $property->name }}</h5>
                                                <p class="dia_chi"><i class="fas fa-map-marker-alt"></i> {{ $property->location }}</p>
                                                <p class="bold500">{{ __('Price') }}: {{ format_price($property->price, $property->currency) }}</p>
                                            </a>
                                            <p class="threemt bold500">
                                                @if ($property->number_bedroom)
                                                    <span data-toggle="tooltip" data-placement="top" data-original-title="{{ __('Number of rooms') }}"> <i><img src="{{ Theme::asset()->url('images/bed.svg') }}" alt="icon"></i> <i class="vti">{{ $property->number_bedroom }}</i> </span>
                                                @endif
                                                @if ($property->number_bathroom)
                                                    <span data-toggle="tooltip" data-placement="top" data-original-title="{{ __('Number of rest rooms') }}">  <i><img src="{{ Theme::asset()->url('images/bath.svg') }}" alt="icon"></i> <i class="vti">{{ $property->number_bathroom }}</i></span>
                                                @endif
                                                @if ($property->square)
                                                    <span data-toggle="tooltip" data-placement="top" data-original-title="{{ __('Square') }}"> <i><img src="{{ Theme::asset()->url('images/area.svg') }}" alt="icon"></i> <i class="vti">{{ $property->square }} {{ __('m2') }}</i> </span>
                                                @endif
                                            </p>
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
        {!! $properties->links() !!}
    </nav>
</div>
<br>
<br>
