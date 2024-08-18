<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\QuestionComment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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
            // バリデーション
            $validatedData = $request->validate([
                'question_id' => 'required|integer|exists:questions,id',
                'comment' => 'required|string|max:255',
            ]);

            // 新しいコメントを作成
            $comment = new QuestionComment();
            $comment->question_id = $validatedData['question_id'];
            $comment->user_id = Auth::id(); // 現在ログインしているユーザーのIDを取得
            $comment->comment = $validatedData['comment'];
            $comment->save();

            // JSONレスポンスを返す
            return response()->json([
                'user' => Auth::user(),
                'comment' => $comment,
            ]);
        } catch (\Exception $e) {
            // エラーメッセージをログに記録
            Log::error('コメント投稿エラー: ' . $e->getMessage());

            return response()->json([
                'error' => 'コメントの投稿に失敗しました。',
            ], 500);
        }
    }

    public function destroy($commentId)
    {
        try {
            // コメントを取得
            $comment = QuestionComment::findOrFail($commentId);

            // ログインユーザーがコメントの所有者であるか確認
            if ($comment->user_id === Auth::id()) {
                $comment->delete();
                return response()->json(['success' => true]);
            } else {
                return response()->json(['error' => '権限がありません。'], 403);
            }
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['error' => 'コメントが見つかりません。'], 404);
        } catch (\Exception $e) {
            // その他のエラーを処理
            Log::error('コメント削除エラー: ' . $e->getMessage());

            return response()->json(['error' => 'コメントの削除に失敗しました。'], 500);
        }
    }
}
