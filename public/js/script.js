$(document).ready(function() {
    $('.like-button').on('click', function() {
        var postId = $(this).data('id'); // ボタンに設定されているポストIDを取得
        var isLiked = $(this).hasClass('liked'); // ボタンが「いいね済み」かどうかを確認
        var url = isLiked ? '/first/unlike/' + postId : '/first/like/' + postId; // リクエストURLの決定
        var $button = $(this); // ボタンの jQuery オブジェクトをキャッシュ

        $.ajax({
            url: url, // リクエストの URL
            method: 'POST', // HTTP メソッド
            data: {
                _token: $('meta[name="csrf-token"]').attr('content') // CSRF トークン
            },
            success: function(response) {
                $button.toggleClass('liked'); // 「いいね」クラスのトグル
                $button.find('.like-count').text(response.count); // いいねの数を更新
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', status, error); // エラー処理
            }
        });
    });
});
