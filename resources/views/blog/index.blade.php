@extends('master')
@section('title', 'Post')
@section('content')

    <div class="container">
        <a class="pi-button" href="{{url('/posts/create')}}">New post</a>
        @if ($posts->isEmpty())
            <p> There is no post.</p>
        @else
            @foreach ($posts as $post)
                <div class="post-item">
                    <div class="post-title"><strong>{!! $post->id !!}</strong>. {!! $post->title !!}</div>
                    <div class="post-body">
                        {!! mb_substr($post->content, 0, 500) !!}
                    </div>

                    <div class="post-detail">
                        <div class="post-fromdate">
                            <em>From date: </em> {!! $post->fromdate !!}
                        </div>
                        <div class="post-todate">
                            <em>To date:   </em> {!! $post->todate !!}
                        </div>
                        <div class="post-status">
                            <em>Status : </em>{!! $post->status !!}
                        </div>
                    </div>
                    <div class = "post-action">
                        <a href="{{Url('/posts/edit/' . $post->id)}}" class="btn btn-primary" data-size="xs">Edit</a>
                    </div>
                </div>
            @endforeach
        @endif
        {!! $posts->render() !!}
    </div>

@endsection
