<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PostComment; 
use Illuminate\Support\Facades\Auth;

class PostCommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

   public function store(Request $request)
    {
        try {
            $request->validate([
                'post_id' => 'required|integer|exists:posts,id',
                'comment' => 'required|string|max:255',
            ]);
    
            $comment = new PostComment();
            $comment->post_id = $request->input('post_id');
            $comment->user_id = auth()->id();
            $comment->comment = $request->input('comment');
            $comment->save();
    
            return response()->json([
                'user' => auth()->user(),
                'comment' => $comment
            ]);
        } catch (\Exception $e) {
            \Log::error('コメント投稿エラー: ' . $e->getMessage());
    
            return response()->json([
                'error' => 'コメントの投稿に失敗しました。',
                'details' => $e->getMessage()  // 詳細なエラーメッセージを追加
            ], 500);
        }
    }

    
    public function destroy($commentId)
    {
        $comment = PostComment::find($commentId);
    
        if ($comment) {
            if ($comment->user_id === Auth::id()) {
                $comment->delete();
                return response()->json(['success' => true]);
            } else {
                return response()->json(['error' => '権限がありません。'], 403);
            }
        }
    
        return response()->json(['error' => 'コメントが見つかりません。'], 404);
    }
}
