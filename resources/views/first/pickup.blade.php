<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
    </head>
    <x-app-layout>
        @foreach($posts as $post)
                    <div class="content">
                        <div>
                            <img src="{{ $post->image_url }}" alt="画像が読み込めません。">
                        </div>
                        <div class="title">
                            <a href="{{ route('posts', ['post' => $post->id]) }}">{{ $post->title }}</a>
                        </div>
                        <div>
                            @if($post->is_liked_by_auth_user())
                                <a href="{{ route('post.unlike', ['id' => $post->id]) }}" class="btn btn-success btn-sm"><ion-icon name="heart"></ion-icon><span class="badge">{{ $post->likes->count() }}</span></a>
                            @else
                                <a href="{{ route('post.like', ['id' => $post->id]) }}" class="btn btn-secondary btn-sm"><ion-icon name="heart-outline"></ion-icon><span class="badge">{{ $post->likes->count() }}</span></a>
                            @endif
                        </div>
                    </div>
                @endforeach
        <div class="footer">
            <a href="/index">戻る</a>
        </div>
    </x-app-layout>
</html>
