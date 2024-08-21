<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <title>Question Show</title>
    <link rel="stylesheet" href="{{ asset('/css/question/show.css') }}">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <x-app-layout>
        <h2 style="text-align: center;" class="question_title">Question Details</h2>
        <div class="question-details">
            <div class="question">
                <div class="images">
                    @if (session('error'))
                        <div class="alert">
                            {{ session('error') }}
                        </div>
                    @endif
                    @php
                        $imageUrls = json_decode($question->image_urls, true) ?? [];
                        $isSingleImage = count($imageUrls) === 1;
                    @endphp
                    <div class="image-grid {{ $isSingleImage ? 'single-image' : '' }}">
                        @if (!empty($imageUrls))
                            @foreach ($imageUrls as $imageUrl)
                                <div class="image-item">
                                    <img src="{{ $imageUrl }}" alt="Image">
                                </div>
                            @endforeach
                        @else
                            <p>No images available.</p>
                        @endif
                    </div>    
                </div>

                <div class="content">
                    <div class="content__question">
                        <h4 class="title font-semibold leading-tight">{{ $question->title }}</h4>
                        <p class="comment">{{ $question->comment }}</p>
                    </div>
                    @if ($question->user)
                        <small>{{ $question->user->name }}</small>
                    @else
                        <small>Unknown user</small>
                    @endif
                </div>
                <form action="/question/{{ $question->id }}" id="form_{{ $question->id }}" method="post">
                    @csrf
                    @method('DELETE')
                    <button type="button" onclick="deleteQuestion({{ $question->id }})">削除</button>
                </form>
            </div>
        </div>
        <div class="footer">
            <a href="/">戻る</a>
        </div>
        <script>
            
                function deleteQuestion(id) {
                    'use strict';
            
                    if (confirm('削除すると復元できません。\n本当に削除しますか？')) {
                        document.getElementById(`form_${id}`).submit();
                    }
                }
            

        </script>

        <h1 style="text-align:center;" class="question_title">Comments for Question</h1>
    
        <!-- コメント追加フォーム -->
        <form id="commentForm" method="POST">
            @csrf
            <input type="hidden" name="question_id" value="{{ $question->id }}">
            <textarea name="comment" rows="4" required></textarea>
            <button type="submit">返信</button>
        </form>
    
        <!-- コメント表示部分 -->
        <ul id="commentList">
            @if($question->comments && $question->comments->count() > 0)
                @foreach ($question->comments as $comment)
                    <li id="comment_{{ $comment->id }}">
                        {{ $comment->user->name }}: {{ $comment->comment }}
                        @if ($comment->user_id === Auth::id())
                            <i class="fa-solid fa-ellipsis" style="color: #dc324c; cursor: pointer;" onclick="toggleDeleteMenu({{ $comment->id }})"></i>
                            <!-- 削除メニュー -->
                            <div id="deleteMenu_{{ $comment->id }}" class="delete-menu" style="display:none;">
                                <button onclick="deleteComment({{ $comment->id }})">削除</button>
                            </div>
                        @endif
                    </li>
                @endforeach
            @else
                <li>コメントはまだありません。</li>
            @endif
        </ul>
    </x-app-layout>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // CSRFトークンをAJAXリクエストのヘッダーに設定
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('#commentForm').on('submit', function(e) {
                e.preventDefault();

                var questionId = "{{ $question->id }}";

                $.ajax({
                    type: 'POST',
                    url: `/question/${questionId}/comments`,
                    data: $(this).serialize(),
                    success: function(response) {
                        // 新しいコメントをリストに追加
                        $('#commentList').append('<li id="comment_' + response.comment.id + '">' + response.user.name + ': ' + response.comment.comment + '</li>');
                        // フォームをクリア
                        $('#commentForm textarea[name="comment"]').val('');
                    },
                    error: function(error) {
                        console.log('AJAXエラー:', error);
                        alert('コメントの投稿に失敗しました。');
                    }
                });
            });
        });

        function toggleDeleteMenu(commentId) {
            const menu = document.getElementById(`deleteMenu_${commentId}`);
            menu.style.display = menu.style.display === 'none' ? 'block' : 'none';
        }

        function deleteComment(commentId) {
            if (confirm('本当に削除しますか？')) {
                $.ajax({
                    type: 'DELETE',
                    url: `/question/comments/${commentId}`,
                    success: function(response) {
                        if (response.success) {
                            // 削除成功後、コメントをリストから削除
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
        }
    </script>
</body>
</html>
