<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="UTF-8">
        <title>index</title>
        <link rel="stylesheet" href="index.css">
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
    </head>
    <x-app-layout>
    
        <h3>投稿内容</h3>
        <div class='posts'>
            <form action="/posts/questions" method"POST">
            @csrf
            @foreach ($questions as $question)
                <div class='question'>
                    <p class='title'>{{ $question->title }}</p>
                    <p class='comment'>{{ $question->comment }}</p>
                    <p class='tag'>{{$question->tag}}</p>
                    <p class='image'>{{$question->image}}</p>
                </div>
            @endforeach
        </div>
        </form>
    
    </x-app-layout>
</html>