<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <title>Question Show</title>
    <link rel="stylesheet" href="{{ asset('/css/posts.css') }}">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
</head>
<x-app-layout>
    <h2 style="text-align: center;">Question Details</h2>
    <div class="question-details">
        <div class="question">
            <div class="images">
                @if (session('error'))
                    <div class="alert">
                        {{ session('error') }}
                    </div>
                @endif
                @php
                    $imageUrls = json_decode($question->image_urls, true) ?? [];
                    $isSingleImage = count($imageUrls) === 1;
                @endphp
                <div class="image-grid {{ $isSingleImage ? 'single-image' : '' }}">
                    @if (!empty($imageUrls))
                        @foreach ($imageUrls as $imageUrl)
                            <div class="image-item">
                                <img src="{{ $imageUrl }}" alt="Image">
                            </div>
                        @endforeach
                    @else
                        <p>No images available.</p>
                    @endif
                </div>    
            </div>

            <div class="content">
                <div class="content__question">
                    <h4 class="title font-semibold leading-tight">{{ $question->title }}</h4>
                    <p class="comment">{{ $question->comment }}</p>
                </div>
                @if ($question->user)
                    <small>{{ $question->user->name }}</small>
                @else
                    <small>Unknown user</small>
                @endif
            </div>
            <form action="/question/{{ $question->id }}" id="form_{{ $question->id }}" method="post">
                @csrf
                @method('DELETE')
                <button type="button" onclick="deleteQuestion({{ $question->id }})">削除</button>
            </form>
        </div>
    </div>
    <div class="footer">
        <a href="/">戻る</a>
    </div>
    <script>
        function deleteQuestion(id) {
            'use strict';

            if (confirm('削除すると復元できません。\n本当に削除しますか？')) {
                document.getElementById(`form_${id}`).submit();
            }
        }
    </script>
</x-app-layout>
</html>
