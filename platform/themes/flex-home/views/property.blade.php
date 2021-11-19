<main class="detailproject" style="background: #fff;">
    <div class="boxsliderdetail">
        <div class="slidetop">
            <div class="owl-carousel" id="listcarousel">
                @foreach ($property->images as $image)
                    <div class="item"><img  src="{{ get_object_image($image) }}" class="showfullimg" rel="{{ $loop->index }}" alt="{{ $property->name }}"></div>
                @endforeach
            </div>
        </div>
        <div class="slidebot">
            <div style="max-width: 800px; margin: 0 auto;">
                <div class="owl-carousel" id="listcarouselthumb">
                    @foreach($property->images as $image)
                        <div class="item cthumb" rel="{{ $loop->index }}"><img  src="{{ get_object_image($image) }}" class="showfullimg" rel="{{ $loop->index }}" alt="{{ $property->name }}"></div>
                    @endforeach
                </div>
                <i class="fas fa-chevron-right ar-next"></i>
                <i class="fas fa-chevron-left ar-prev"></i>
            </div>
        </div>
    </div>
    <div id="gallery" data-images="{{ json_encode($images) }}"></div>

    <div class="container-fluid w90 padtop20">
        <h1 class="titlehouse" id="house-40396">{{ $property->name }}</h1>
        <p class="addresshouse"><i class="fas fa-map-marker-alt"></i>  {{ $property->location }}</p>
        <p class="pricehouse"> {{ format_price($property->price, $property->currency) }} {!! $property->status->toHtml() !!}</p>
        <div class="row">
            <div class="col-md-8">
                <div class="row" style="padding-top: 15px;">
                    <div class="col-sm-12">
                        <h5 class="headifhouse">{{ __('Overview') }}</h5>
                        <div class="row" style="padding:10px 0;">
                            <div class="col-sm-12">
                                <table width="100%">
                                    <tr>
                                        <td>{{ __('Category') }}</td>
                                        <td><b>{{ $property->category->name }}</b></td>
                                    </tr>
                                    @if ($property->square)
                                    <tr>
                                        <td>{{ __('Square') }}</td>
                                        <td><b>{{ $property->square }} {{ __('m2') }}</b></td>
                                    </tr>
                                    @endif
                                    @if ($property->number_bedroom)
                                        <tr>
                                            <td>{{ __('Number of bedrooms') }}</td>
                                            <td><b>{{ $property->number_bedroom }}</b></td>
                                        </tr>
                                    @endif
                                    @if ($property->number_bathroom)
                                        <tr>
                                            <td>{{ __('Number of bathrooms') }}</td>
                                            <td><b>{{ $property->number_bathroom }}</b></td>
                                        </tr>
                                    @endif
                                    <tr>
                                        <td>{{ __('Price') }}</td>
                                        <td><b>{{ format_price($property->price, $property->currency) }}</b></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                @if ($property->content)
                    <div class="row">
                        <div class="col-sm-12">
                            <h5 class="headifhouse">{{ __('Description') }}</h5>
                            {!! $property->content !!}
                        </div>
                    </div>
                @endif
                @if ($property->features->count())
                    <div class="row">
                        <div class="col-sm-12">
                            <h5 class="headifhouse">{{ __('Features') }}</h5>
                            <div class="row">
                                @foreach($property->features as $feature)
                                    <div class="col-sm-4">
                                        <p><i class="fas fa-check text-orange text0i"></i>  {{ $feature->name }}</p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
                @if ($property->project_id)
                    <div class="row" style="padding-bottom: 20px;">
                        <div class="col-sm-12">
                            <h5 class="headifhouse">{{ __('Project\'s information') }}</h5>
                        </div>
                        <div class="col-sm-12 projecthome">
                            <div class="row item">
                                <div class="col-md-4">
                                    <div class="img"><a href="{{ $property->project->url }}"><img class="thumb lazy" data-src="{{ get_object_image($property->project->image) }}" src="{{ get_object_image($property->project->image) }}"  alt="{{ $property->project->name }}"></a>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <h5><a href="{{ $property->project->url }}" style="color:#222;font-weight: 600;">{{ $property->project->name }}</a></h5>
                                    <div>{{ Str::words($property->project->description, 120) }}</div>
                                    <p><a href="{{ $property->project->url }}" style="color:#04327e;">{{ __('Read more') }}</a></p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                <br>
                <div class="mapouter">
                    <div class="gmap_canvas">
                        <iframe id="gmap_canvas" width="100%" height="500"
                                src="https://maps.google.com/maps?q={{ urlencode($property->location) }}%20&t=&z=13&ie=UTF8&iwloc=&output=embed"
                                frameborder="0" scrolling="no" marginheight="0" marginwidth="0"></iframe>
                    </div>
                </div>
                <br>
                <br>
                {!! Theme::partial('share', ['title' => __('Share this property'), 'description' => $property->description]) !!}
                <div class="clearfix"></div>
                <br>
            </div>
            <div class="col-md-4">
                @if ($property->author->id)
                    <div class="boxright">
                        <div class="head">
                            {{ __('Contact agency') }}
                        </div>

                        <div class="row rowm10 itemagent">
                            <div class="col-sm-4 colm10">
                                <img src="{{ $property->author->avatar_url }}" alt="{{ $property->author->getFullName() }}" class="img-thumbnail">
                            </div>
                            <div class="col-sm-8 colm10">
                                <div class="info">
                                    <p><strong>{{ $property->author->getFullName() }}</strong></p>
                                    <p class="mobile">{{ $property->author->phone }}</p>
                                    <p>{{ $property->author->email }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                <div class="boxright">
                    {!! Theme::partial('consult-form', ['type' => 'property', 'data' => $property]) !!}
                </div>
            </div>
        </div>
        <h5  class="headifhouse">{{ __('Related properties') }}</h5>
        <property-component type="related" url="{{ route('public.ajax.properties') }}" property_id="{{ $property->id }}"></property-component>
        <br>
        <br>
        <br>

    </div>
</main>
