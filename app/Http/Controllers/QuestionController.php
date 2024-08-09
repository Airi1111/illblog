<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Question;
use App\Http\Requests\QuestionRequest;
use App\Models\Post;
use App\Models\Like;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Log;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use App\Helpers\ImageUploadHelper;


class QuestionController extends Controller
{


    public function home(Post $post, Request $request)
    {
        $posts = Post::where('user_id', Auth::id())
                     ->orderBy('created_at', 'DESC')
                     ->limit(6)
                     ->get();
        foreach ($posts as $post) {
            $post->image_url = json_decode($post->image_urls, true)[0] ?? null;
        }
        return view('dashboard', ['posts' => $posts]);
    }

    public function questionpick()
    {
        $questions = Question::with('likes', 'user') // リレーションを事前にロード
        ->where('user_id', Auth::id())
        ->get();
         return view('question.pickquestions', compact('questions'));
    }


    public function create()
    {
        return view('question.create');
    }

    public function store(QuestionRequest $request, Question $question)
    {
        $input = $request->input('question');
        $input['user_id'] = Auth::id();
    
        if ($request->hasFile('question.images')) {
            try {
                $imageUrls = ImageUploadHelper::uploadImages($request->file('question.images'));
                $input['image_urls'] = json_encode($imageUrls);
            } catch (\Exception $e) {
                return back()->with('error', $e->getMessage());
            }
        }
    
        $question->fill($input)->save();
        return redirect()->route('question.show', ['question' => $question->id]);
    }

    


    public function myquestions()
    {
        $questions = Question::where('user_id', Auth::id())->get();
        return view('question.myquestions', compact('questions'));
    }


    public function update(QuestionRequest $request, Question $question)  // 修正箇所
    {
        $input = $request->input('question');
        $input['user_id'] = $request->user()->id;
        $question->fill($input)->save();
        return redirect()->route('question.show', ['question' => $question->id]);
    }

    public function delete(Question $question)
    {
        $question->delete();
        return redirect()->route('home');
    }

    public function myPosts()
    {
        $userId = Auth::id();
        $posts = Post::where('user_id', $userId)->get();
        return view('dashboard', ['posts' => $posts]);
    }

    public function show(Question $question)
    {
        return view('question.show', compact('question'));
    }
    public function __construct()
    {
        $this->middleware('auth');
    }

}
