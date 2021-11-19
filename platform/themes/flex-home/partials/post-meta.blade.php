<p class="post-meta">
    <small>{{ __('Posted At') }}: {{ $post->created_at->format('Y/m/d') }} {{ __('in') }} @foreach($post->categories as $category)
            <a href="{{ $category->url }}">{{ $category->name }}</a>
            @if (!$loop->last)
                ,
            @endif
        @endforeach
    </small>
</p>
