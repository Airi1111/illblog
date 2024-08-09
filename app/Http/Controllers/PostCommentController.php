<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostCommentRequest;
use App\Models\Post;
use App\Models\PostComment; 
use Illuminate\Support\Facades\Auth;

class PostCommentController extends Controller
{
    public function store(PostCommentRequest $request, $postId)
    {
        $post = Post::findOrFail($postId);

        // コメントの保存
        $comment = new PostComment(); 
        $comment->user_id = Auth::id(); 
        $comment->post_id = $postId;
        $comment->comment = $request->input('comment');
        $comment->save();

        // 成功時のリダイレクト
        return redirect()->route('posts', ['post' => $postId])->with('success', 'コメントが投稿されました。');
    }

    public function destroy($id)
    {
        $comment = PostComment::findOrFail($id); 
        $comment->delete();

        return redirect()->back()->with('success', 'コメントが削除されました。');
    }
}
