<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
        <link rel="stylesheet" href="{{ asset('/css/pickup.css') }}">
    </head>
    <x-app-layout>
        @foreach($posts as $post)
            <div class="content">
                <div class="img">
                    @if($post->image_url)
                         <a href="{{ route('posts', ['post' => $post->id]) }}"><img src="{{ $post->image_url }}" alt="画像が読み込めません。" ></a>
                    @else
                        <p>画像がありません。</p>
                    @endif
                    <div class="title-and-likes">
                        <div class="title">
                            <h4><a href="{{ route('posts', ['post' => $post->id]) }}">{{ $post->title }}</a></h4>
                        </div>
                        <div class="likes">
                            @if($post->is_liked_by_auth_user())
                                <a href="{{ route('post.unlike', ['id' => $post->id]) }}" class="btn btn-success btn-sm">
                                    <ion-icon name="heart"></ion-icon><span class="badge">{{ $post->likes->count() }}</span>
                                </a>
                            @else
                                <a href="{{ route('post.like', ['id' => $post->id]) }}" class="btn btn-secondary btn-sm">
                                    <ion-icon name="heart-outline"></ion-icon><span class="badge">{{ $post->likes->count() }}</span>
                                </a>
                            @endif
                        </div>
                    </div>
                    <div class="username">
                        <small>{{ $post->user->name }}</small>
                    </div>
                </div>
            </div>
        @endforeach

        <div class="return">
            <a href="{{ route('index') }}" class="return" type="button">戻る</a>
        </div>
    </x-app-layout>
</html>
