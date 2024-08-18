<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <title>Post Show</title>
    <link rel="stylesheet" href="{{ asset('/css/posts.css') }}">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
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
                            <p class="no-images">No images available.</p>
                        @endif
                    </div>
                </div>

                <div class="content">
                    <div class="content__post">
                        <h4 class="title">{{ $post->title }}</h4>
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
                    <button type="button" onclick="deletePost({{ $post->id }})" class="delete-button">削除</button>
                </form>
            </div>
        </div>
        <div class="footer">
            <a href="/">戻る</a>
        </div>
        <!-- コメント追加フォーム -->
        <div id="commentForm">
            <form id="newCommentForm" method="POST" action="{{ route('postcomments.store', ['post' => $post->id]) }}">
                @csrf
                <input type="hidden" name="post_id" value="{{ $post->id }}">
                <textarea name="comment" required></textarea>
                <button type="submit">コメントを追加</button>
            </form>
        </div>
        <!-- コメント表示部分 -->
        @if($post->comments->isNotEmpty())
            <ul id="commentList">
                @foreach($post->comments as $comment)
                    <li class="comment-item" id="comment_{{ $comment->id }}">
                        <p>{{ $comment->user->name }}: {{ $comment->comment }}</p>
                        @if(Auth::id() === $comment->user_id)
                            <button onclick="toggleDeleteMenu({{ $comment->id }})">...</button>
                            <form id="deleteMenu_{{ $comment->id }}" class="delete-comment-menu" style="display: none;">
                                @csrf
                                @method('DELETE')
                                <button type="button" onclick="deleteComment({{ $comment->id }})" class="delete-comment-button">削除</button>
                            </form>
                        @endif
                    </li>
                @endforeach
            </ul>
        @else
            <p>コメントはありません。</p>
        @endif
    </x-app-layout>

    <!-- jQueryの読み込み -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('#newCommentForm').on('submit', function(e) {
                e.preventDefault();

                var postId = "{{ $post->id }}"; // 現在の投稿IDを取得

                $.ajax({
                    type: 'POST',
                    url: `/first/${postId}/comments`,
                    data: $(this).serialize(),
                    success: function(response) {
                        $('#commentList').append('<li class="comment-item" id="comment_' + response.comment.id + '">'
                            + response.user.name + ': ' + response.comment.comment + '</li>');
                        $('#newCommentForm textarea[name="comment"]').val('');
                    },
                    error: function(error) {
                        console.log('AJAXエラー:', error);
                        alert('コメントの投稿に失敗しました。');
                    }
                });
            });

            // コメント削除機能
            window.deleteComment = function(commentId) {
                if (confirm('本当に削除しますか？')) {
                    $.ajax({
                        type: 'DELETE',
                        url: `/first/comments/${commentId}`,
                        success: function(response) {
                            if (response.success) {
                                $('#comment_' + commentId).remove();
                            } else {
                                alert(response.error);
                            }
                        },
                        error: function(error) {
                            console.log('AJAXエラー:', error);
                            alert('コメントの削除に失敗しました。');
                        }
                    });
                }
            };

            // コメント削除メニューの表示/非表示
            window.toggleDeleteMenu = function(commentId) {
                const menu = document.getElementById(`deleteMenu_${commentId}`);
                menu.style.display = menu.style.display === 'none' ? 'block' : 'none';
            };
        });
    </script>
</body>
</html>
