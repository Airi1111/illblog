<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>Home</title>
    <link rel="stylesheet" href="{{ asset('/css/home.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</head>
<body>
    <x-app-layout>
        <div class="home">
            <!-- MY WORKS Header -->
            <div class="header-wrapper">
                <h3 class="font-semibold leading-tight">{{ $user->name }} WORKS</h3>
                <div class="round-frame">
                    <div class="profile-and-username">
                        @if ($user->profile_image)
                        <div class="profile-image-container">
                            <a href="{{ route('profile') }}">
                                <img src="{{ $user->profile_image }}" alt="Profile Image" class="profile-image">
                            </a>
                        </div>
                        @else
                            <p>No profile image available.</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Works Section -->
            <div class="works">
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
                    </div>
                </div>
                @endforeach
            </div>

            <!-- More Button for Works -->
            <div class="more">
                <a href="{{ route('pickup') }}" class="pickmore" type="button">もっと見る</a>
            </div>

            <!-- MY QUESTIONS Header -->
            <div class="header-wrapper">
                <h3 class="font-semibold leading-tight">{{ $user->name }} QUESTIONS</h3>
            </div>

            <!-- Questions List -->
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

            <!-- More Button for Questions -->
            <div class="more">
                <a href="#" class="more" type="button">もっと見る</a>
            </div>

            <!-- User Info Section -->
            <div class="py-12">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900">
                            {{ Auth::user()->name }}{{ __("：ログイン中...") }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script src="{{ asset('js/script.js') }}"></script>
    </x-app-layout>
</body>
</html>
