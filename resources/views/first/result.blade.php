<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <title>Search Results</title>
    <link rel="stylesheet" href="{{ asset('/css/search.css') }}">
</head>
<x-app-layout>
    <h3>検索結果</h3>
    @if($posts->isEmpty())
        <p>該当する投稿はありません。</p>
    @else
        @foreach($posts as $post)
            <div class="post">
                <h4>{{ $post->title }}</h4>
                <p>{{ $post->tag }}</p>
                <p>{{ $post->content }}</p> <!-- content フィールドを追加 -->
                <a href="{{ route('posts', ['post' => $post->id]) }}">詳細を見る</a>
            </div>
        @endforeach
    @endif
    <div class="footer">
        <a href="/">戻る</a>
    </div>
</x-app-layout>
</html>
