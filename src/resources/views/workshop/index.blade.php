@foreach ($workshops as $workshop)
    <div>
        <a href="{{$workshop->event_url}}">{{$workshop->title}}</a>
        <ul>
            @foreach($workshop->tags as $tag)
                <li>{{$tag->name}}</li>
            @endforeach
        </ul>
    </div>
@endforeach
