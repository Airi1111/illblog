<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <title>postshow</title>
    <link rel="stylesheet" href="{{ asset('/css/posts.css') }}">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
</head>
<x-app-layout>
    <h2 style="text-align: center;">CREATE POST</h2>
    <div class="posts">
        <div class="post">
            <div class="images">
                @if (session('error'))
                    <div class="alert">
                        {{ session('error') }}
                    </div>
                @endif
                @php
                    $imageUrls = json_decode($post->image_urls);
                    $isSingleImage = count($imageUrls) === 1;
                @endphp
                <div class="image-grid {{ $isSingleImage ? 'single-image' : '' }}">
                    @if ($post->image_urls)
                        @foreach ($imageUrls as $imageUrl)
                            <div class="image-item">
                                <img src="{{ $imageUrl }}" alt="Image">
                            </div>
                        @endforeach
                    @endif
                </div>    
            </div>

            <div class="content">
                <div class="content__post">
                    <h4 class="title font-semibold leading-tight">{{ $post->title }}</h4>
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
