<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>検索結果</title>
    <link rel="stylesheet" href="{{ asset('/css/result.css') }}">
</head>
<x-app-layout>
<div class='index'>
    <div class="search-container">
        <form action="{{ route('searchForm') }}" method="GET">
            <input type="text" name="keyword" placeholder="キーワードを入力" value="{{ old('keyword', $keyword) }}" class="search-input">
            <button type="submit" class="search-button">検索</button>
        </form>
    </div>

    <h3 class="font-semibold leading-tight">検索結果</h3>
    @if(!empty($keyword))
        <p>検索キーワード: {{ $keyword }}</p>
    @endif

    @if($posts->isEmpty())
        <p>結果が見つかりませんでした。</p>
    @else
        <div class="pickups">
            <div class="item">
                @foreach($posts as $post)
                    <div class="content">
                        <div class="img">
                           
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
            </div>
        </div>
    @endif
</div>

<script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</x-app-layout>
</html>
