<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class QuestionCommentsController extends Controller
{
    public function __construct()
    {
        // ログインしていなかったらログインページに遷移する（この処理を消すとログインしなくてもページを表示する）
        $this->middleware('auth');
    }

   public function store(Request $request)
   {
       $comment = new Comment();
       $comment->comment = $request->comment;
       $comment->question_id = $request->question_id;
       $comment->user_id = Auth::user()->id;
       $comment->save();

       return redirect('question.show');
   }

    public function destroy(Request $request)
    {
        $comment = Comment::find($request->comment_id);
        $comment->delete();
        return redirect('question.show');
    }
}
