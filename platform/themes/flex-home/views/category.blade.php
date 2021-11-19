<div class="bgheadproject hidden-xs">
    <div class="description">
        <div class="container-fluid w90 text-center">
            <h1>{{ $category->name }}</h1>
            <p>{{ $category->description }}</p>
            {!! Theme::partial('breadcrumb') !!}
        </div>
    </div>
</div>
<div class="container padtop50">
    <div class="row">
        <div class="col-sm-9">
            <h1 class="titlenews">{{ $category->name }}</h1>
            @foreach($posts as $post)
                <div class="form-group row itemnews">
                    <div class="col-sm-4">
                        <a href="{{ $post->url }}" title="{{ $post->name }}"><img class="img-thumbnail" data-src="{{ get_object_image($post->image, 'small') }}" src="{{ get_object_image($post->image, 'small') }}" alt="{{ $post->name }}"></a>
                    </div>
                    <div class="col-sm-8">
                        <h3><a class="title" href="{{ $post->url }}" title="{{ $post->name }}">{{ $post->name }}</a></h3>
                        {!! Theme::partial('post-meta', compact('post')) !!}
                        <p>{{ Str::words($post->description, 50) }}</p>
                    </div>
                </div>
            @endforeach
            <br>
            <div class="pagination">
                {!! $posts->links() !!}
            </div>
        </div>
        <div class="col-sm-3">
            {!! dynamic_sidebar('primary_sidebar') !!}
        </div>
    </div>
</div>
<br>
<br>
