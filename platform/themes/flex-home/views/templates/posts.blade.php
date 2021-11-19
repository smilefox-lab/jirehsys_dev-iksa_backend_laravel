@if ($posts->count() > 0)
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
@endif
