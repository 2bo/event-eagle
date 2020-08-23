@foreach($tags as $tag)
    <div>
        <span>{{$tag->name}}</span>
        <ul>
            @foreach($tag->events as $event)
                <li>
                    <a href="{{$event->event_url}}">{{$event->title}}</a>
                </li>
            @endforeach
        </ul>
    </div>
@endforeach
