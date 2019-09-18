@foreach($tags as $tag)
    <div>
        <span>{{$tag->name}}</span>
        <ul>
            @foreach($tag->workshops as $workshop)
                <li>
                    <a href="{{$workshop->event_url}}">{{$workshop->title}}</a>
                </li>
            @endforeach
        </ul>
    </div>
@endforeach
