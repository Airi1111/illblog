<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>index</title>
    <link rel="stylesheet" href="{{ asset('/css/index.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <x-app-layout>
        <div class='index'>
            <h1 class="tag">#Pick Up</h1>
            <div class="pickups">
                <div class="item">
                    @foreach($posts as $post)
                        <div class="content">
                            <div class="img">
                                @if($post->image_url) 
                                    <a href="{{ route('posts', ['post' => $post->id]) }}"><img src="{{ $post->image_url }}" alt="画像が読み込めません。"></a>
                                @else
                                    <p>画像がありません。</p>
                                @endif
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
                            <div class="details">
                                <div class="profile-and-username">
                                    <div class="profile-and-username">
                                        @if ($post->user->profile_image)
                                        <div class="profile-image-container">
                                           
                                            <a href="{{ route('user.followers', ['user' => $post->user->id]) }}">
                                                <img src="{{ $post->user->profile_image }}" alt="Profile Image" class="profile-image">
                                            </a>

                                        </div>
                                        @else
                                            <div class="profile-image-container">
                                                <!-- ビュー内 -->
                                            <a href="{{ route('user.followers', ['user' => $post->user->id]) }}">
                                                <img src="{{ $post->user->profile_image }}" alt="Profile Image" class="profile-image">
                                            </a>

                                            </div>
                                        @endif
                                    </div>
                                    <div class="username">
                                        <small>{{ $post->user->name }}</small>
                                    </div>
                                </div>
                                <div class="title">
                                    <h4><a href="{{ route('posts', ['post' => $post->id]) }}">{{ $post->title }}</a></h4>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="more">
                    <a href="{{ route('pickup') }}" class="pickmore" type="button">もっと見る</a>
                </div>

                <h3 class="tag">#Question</h3>
                <div class="questions-list">
                    @foreach ($questions as $question) 
                        <div class="questions">
                            <h4 class="title font-semibold leading-tight">
                                <a class="question-title" href="{{ route('question.show', ['question' => $question->id]) }}">{{ $question->title }}</a>
                            </h4>
                            <p class="comment">{{ $question->comment }}</p>
                            @if ($question->user)
                                <div class="username">
                                    <small>{{ $question->user->name }}</small>
                                </div>
                            @else
                                <div class="username">
                                    <small>Unknown user</small>
                                </div>
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
    </body>
</html>
