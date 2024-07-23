<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <title>Create Post</title>
    <link rel="stylesheet" href="{{ asset('/css/create.css') }}">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
</head>
<x-app-layout>
    <div class="createview">
        <br>
        <form action="{{ route('store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <br>
            <div class="posts">
                <h3>投稿内容</h3>
                <div class="post">
                    <div class="title">
                        <input type="text" name="post[title]" placeholder="タイトル" required/>
                    </div>
                    <br>
                    <div class="comment">
                        <textarea name="post[comment]" placeholder="コメント本文"></textarea>
                    </div>
                    <div class="image">
                        <input type="file" name="images[]" multiple accept="image/*"/>
                    </div>
                </div>
                <input type="submit" value="投稿する"/>
            </div>
        </form>
        <div class="footer">
            <a href="/">戻る</a>
        </div>
    </div>
</x-app-layout>
</html>
