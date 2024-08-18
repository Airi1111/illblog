$(document).ready(function() {
    $('.like-button').on('click', function() {
        var postId = $(this).data('id');
        var isLiked = $(this).hasClass('liked');
        var url = isLiked ? '/first/unlike/' + postId : '/first/like/' + postId;
        var $button = $(this);

        $.ajax({
            url: url,
            method: 'POST', // POST メソッドを使用
            data: {
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                $button.toggleClass('liked');
                $button.find('.like-count').text(response.count);
            }
        });
    });
});
