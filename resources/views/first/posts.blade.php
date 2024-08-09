<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <title>Post Show</title>
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
                    $imageUrls = json_decode($post->image_urls, true) ?? [];
                    $isSingleImage = count($imageUrls) === 1;
                @endphp
                <div class="image-grid {{ $isSingleImage ? 'single-image' : '' }}">
                    @if (!empty($imageUrls))
                        @foreach ($imageUrls as $imageUrl)
                            @if ($imageUrl) <!-- Check if the image URL is not null or empty -->
                                <div class="image-item">
                                    <img src="{{ $imageUrl }}" alt="Image">
                                </div>
                            @endif
                        @endforeach
                    @else
                        <p class="text-align: center;">No images available.</p>
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
    
    <!-- コメント表示部分 -->
    @if($post->comments->isNotEmpty())
        @foreach($post->comments as $comment)
            <div class="comment">
                <p>{{ $comment->user->name }}: {{ $comment->comment }}</p>
                @if(Auth::id() === $comment->user_id)
                    <form action="{{ route('post.comments.destroy', $comment->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit">削除</button>
                    </form>
                @endif
            </div>
        @endforeach
    @else
        <p>コメントはありません。</p>
    @endif

    <!-- コメント追加フォーム -->
    <form action="{{ route('post.comments.store', $post->id) }}" method="POST">
        @csrf
        <textarea name="comment" required></textarea>
        <button type="submit">コメントを追加</button>
    </form>
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
