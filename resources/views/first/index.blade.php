<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>index</title>
    <link rel="stylesheet" href="{{ asset('/css/index.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">

</head>
<x-app-layout>
    <div class='index'>
        <h3 class="font-semibold leading-tight">#Pick Up</h3>
        <div class="pickups">
            <div class="item">
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
                                 
                                    <button class="like-button" data-id="{{ $post->id }}">
                                        <ion-icon name="{{ $post->is_liked_by_auth_user() ? 'heart' : 'heart-outline' }}"></ion-icon>
                                        <span class="like-count">{{ $post->likes->count() }}</span>
                                    </button>
                                    
                                    <a href="{{ route('post.like', ['id' => $post->id]) }}" 
                                       class="btn btn-secondary btn-sm like-btn" 
                                       data-post-id="{{ $post->id }}" 
                                       data-action="like">
                                        <ion-icon name="heart-outline"></ion-icon><span class="badge">{{ $post->likes->count() }}</span>
                                    </a>
                                        </div>
                                 @if ($post->user->profile_image_url)
                                    <div class="profile-image-container">
                                        <img src="{{ $post->user->profile_image_url }}" alt="Profile Image" class="profile-image">
                                    </div>
                                @else
                                    <p>No profile image available.</p>
                                @endif
                            </div>
                            <div class="username">
                                <small>{{ $post->user->name }}</small>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="more">
                <a href="{{ route('pickup') }}" class="pickmore" type="button">もっと見る</a>
            </div>
        </div>

        <h3 class="font-semibold leading-tight">#Question</h3>
        <div class="questions-list">
            @foreach ($questions as $question) 
                <div class="questions">
                    <h4 class="title font-semibold leading-tight">
                        <a href="{{ route('question.show', ['question' => $question->id]) }}">{{ $question->title }}</a>
                    </h4>
                    <p class="comment">{{ $question->comment }}</p>
                    @if ($question->user)
                        <small>{{ $question->user->name }}</small>
                    @else
                        <small>Unknown user</small>
                    @endif
                </div>
            @endforeach
        </div>
        <div class="more">
            <a href="{{ route('pick.questions') }}" class="more" type="button">もっと見る</a>
        </div>
    </div>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ Auth::user()->name }}{{ __("：ログイン中...") }}
                </div>
            </div>
        </div>
    </div>

    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    <script src="{{ asset('js/script.js') }}"></script>

</x-app-layout>
</html>
