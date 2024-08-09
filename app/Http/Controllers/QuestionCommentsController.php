<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\QuestionComment; 
use Illuminate\Support\Facades\Auth;

class QuestionCommentsController extends Controller
{
    public function __construct()
    {
        // ログインしていなかったらログインページに遷移する
        $this->middleware('auth');
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'question_id' => 'required|integer|exists:questions,id',
                'comment' => 'required|string|max:255',
            ]);
    
            $comment = new QuestionComment();
            $comment->question_id = $request->input('question_id');
            $comment->user_id = auth()->id(); // 現在ログインしているユーザーのIDを取得
            $comment->comment = $request->input('comment');
            $comment->save();
    
            return response()->json([
                'user' => auth()->user(),
                'comment' => $comment
            ]);
        } catch (\Exception $e) {
            // エラーメッセージをログに記録
            \Log::error('コメント投稿エラー: ' . $e->getMessage());
    
            return response()->json([
                'error' => 'コメントの投稿に失敗しました。'
            ], 500);
        }
    }
    

    public function destroy($commentId)
    {
        $comment = QuestionComment::find($commentId);
    
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
