@extends('layouts.app')

@section('content')
    <div class="container">
        @foreach($workshops as $workshop)
            <article class="media">
                <div class="media-content">
                    <div class="content">
                        <p>
                            <strong>
                                <a href="{{$workshop->event_url}}" target="_blank"
                                   rel="noopener">{{$workshop->title}}</a>
                            </strong>
                            {{$workshop->started_at}}
                            <br>
                            {{$workshop->catch}}
                            <br>
                            <small>{{$workshop->address}}</small>
                        </p>
                    </div>
                    <nav class="level is-mobile">
                        <div class="level-left">
                            @foreach($workshop->tags as $tag)
                                <a href="" class="level-item">
                                    <span class="tag">{{$tag->name}}</span>
                                </a>
                            @endforeach
                        </div>
                    </nav>
                </div>
            </article>
        @endforeach
    </div>
    <div class="container">
        {{ $workshops->links() }}
    </div>
@endsection

