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
        <div class="posts">
            @foreach($questions as $question)
                <div class="content">
                    <div class="question">
                        
                        <div class="title-and-likes">
                            <div class="title">
                                <h4><a href="{{ route('question.show', ['question' => $question->id]) }}">{{ $question->title }}</a></h4>
                            </div>
                            <div class="likes">
                                @php
                                    $likesCount = $question->likes ? $question->likes->count() : 0;
                                @endphp
                                @if($question->is_liked_by_auth_user())
                                    <a href="{{ route('post.unlike', ['id' => $question->id]) }}" class="btn btn-success btn-sm">
                                        <ion-icon name="heart"></ion-icon><span class="badge">{{ $likesCount }}</span>
                                    </a>
                                @else
                                    <a href="{{ route('post.like', ['id' => $question->id]) }}" class="btn btn-secondary btn-sm">
                                        <ion-icon name="heart-outline"></ion-icon><span class="badge">{{ $likesCount }}</span>
                                    </a>
                                @endif
                            </div>

                        </div>
                        <div class="username">
                            <small>{{ $question->user->name }}</small>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="return">
            <a href="{{ route('index') }}" class="return" type="button">戻る</a>
        </div>
         <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    </x-app-layout>
</html>
