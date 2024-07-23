<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <title>postshow</title>
    <link rel="stylesheet" href="{{ asset('/css/posts.css') }}">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
    <style>
        .posts img {
            max-width: 100%;
            height: auto;
            display: block;
            margin: 10px 0;
        }
        .alert {
            color: red;
            font-weight: bold;
            margin-bottom: 10px;
        }
    </style>
</head>
<x-app-layout>
    <h3 style="text-align: center;">投稿内容</h3>
    <div class="posts">
        <div class="post">
            @if (session('error'))
                <div class="alert">
                    {{ session('error') }}
                </div>
            @endif
            @if ($post->image_urls)
                @foreach (json_decode($post->image_urls) as $imageUrl)
                    <img src="{{ $imageUrl }}" alt="Image">
                @endforeach
            @endif

            <div class="content">
                <div class="content__post">
                    <h4 class="title">{{ $post->title }}</h4>
                    <p class="comment">{{ $post->comment }}</p>
                    <p class="tag">{{ $post->tag }}</p>
                </div>
                <small>{{ $post->user->name }}</small>
            </div>
            <form action="/first/{{ $post->id }}" id="form_{{ $post->id }}" method="post">
                @csrf
                @method('DELETE')
                <button type="button" onclick="deletePost({{ $post->id }})">削除</button>
            </form>
        </div>
    </div>
    <div class="footer">
        <a href="/">戻る</a>
    </div>
    <script>
        function deletePost(id) {
            'use strict';

            if (confirm('削除すると復元できません。\n本当に削除しますか？')) {
                document.getElementById(`form_${id}`).submit();
            }
        }
    </script>
</x-app-layout>
</html>
