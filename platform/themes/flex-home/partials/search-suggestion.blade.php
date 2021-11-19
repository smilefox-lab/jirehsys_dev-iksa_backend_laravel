<ul>
    @foreach($items as $item)
        <li><p><a href="{{ $item->url }}">{{ $item->name }}</a></p>
            <p>{{ $item->location }}</p>
        </li>
    @endforeach
</ul>
