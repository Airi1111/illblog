<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostRequest;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Question;
use App\Models\Like;
//use Cloudinary;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Log;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use App\Helpers\ImageUploadHelper;

class PostController extends Controller
{
   public function index(Post $post, Request $request)
    {
        // Postのクエリを構築
        $postQuery = Post::withCount('likes')->orderBy('likes_count', 'desc');
        $posts = (new Post)->getPaginateByLimit($postQuery);
        foreach ($posts as $post) {
            $post->image_url = json_decode($post->image_urls, true)[0] ?? null;
        }
    
        // Questionのクエリを構築
        $questionQuery = Question::withCount('likes')->orderBy('likes_count', 'desc');
        $questions = (new Question)->getPaginateByLimit($questionQuery);
        foreach ($questions as $question) {
            $question->image_url = json_decode($question->image_urls, true)[0] ?? null;
        }
    
        // ビューにデータを渡す
        return view('first.index', ['posts' => $posts, 'questions' => $questions]);
    }
    
    public function home(Request $request)
    {
        $postQuery = Post::where('user_id', Auth::id())
                         ->orderBy('created_at', 'DESC');
        $posts = (new Post)->getPaginateByLimit($postQuery);
    
        // 画像URLを設定
        foreach ($posts as $post) {
            $post->image_url = json_decode($post->image_urls, true)[0] ?? null;
        }
    
        $questionQuery = Question::where('user_id', Auth::id())
                                 ->orderBy('created_at', 'DESC');
        $questions = (new Question)->getPaginateByLimit($questionQuery);
    
        // 画像URLを設定
        foreach ($questions as $question) {
            $question->image_url = json_decode($question->image_urls, true)[0] ?? null;
        }
    
        return view('dashboard', ['posts' => $posts, 'questions' => $questions]);
    }


    public function posts(Post $post)
    {
         $post->load('comments.user');
        return view('first.posts', ['post' => $post]);
    }

    public function __construct()
    {
        $this->middleware(['auth', 'verified'])->only(['like', 'unlike']);
    }

    public function like($id)
    {
        Like::create([
            'post_id' => $id,
            'user_id' => Auth::id(),
        ]);

        session()->flash('success', 'You Liked the Reply.');

        return redirect()->back();
    }

    public function unlike($id)
    {
        $like = Like::where('post_id', $id)->where('user_id', Auth::id())->first();
        if ($like) {
            $like->delete();
            session()->flash('success', 'You Unliked the Reply.');
        }

        return redirect()->back();
    }

    public function create()
    {
        return view('first.create');
    }

   public function store(PostRequest $request, Post $post)
    {
        $input = $request->input('post');
        $input['user_id'] = Auth::id();
    
        if ($request->hasFile('post.images')) {
            try {
                $imageUrls = ImageUploadHelper::uploadImages($request->file('post.images'));
    
                // 既存の画像URLを取得
                $existingUrls = $post->image_urls ? json_decode($post->image_urls, true) : [];
    
                // 新しい画像URLを既存の画像URLに追加
                $allUrls = array_merge($existingUrls, $imageUrls);
    
                // 削除された画像の処理
                if ($request->input('post.deleted_images')) {
                    $deletedImages = explode(',', $request->input('post.deleted_images'));
                    $allUrls = array_diff($allUrls, $deletedImages);
                }
    
                // 画像URLをJSON形式で保存
                $input['image_urls'] = json_encode(array_values($allUrls));
            } catch (\Exception $e) {
                return back()->with('error', $e->getMessage());
            }
        }
    
        // 投稿データの保存
        $post->fill($input)->save();
    
        return redirect()->route('posts', ['post' => $post->id]);
    }


    public function pickup(Post $post)
    {
        {
        $posts = $post->allView();
        foreach ($posts as $post) {
            $post->image_url = json_decode($post->image_urls, true)[0] ?? null;
        }
        return view('first.pickup', ['posts' => $posts]);
    }
    }

    public function myworks(Post $post)
    {
        return view('first.myworks', ['posts' => $post->allView()]);
    }

    public function searchForm()
    {
        return view('first.search');
    }

    public function search(Request $request)
    {
        $keyword = $request->input('keyword');
        
        $query = Post::query();
        
        if (!empty($keyword)) {
            $keywords = preg_split('/[\s,]+/', $keyword); // スペースやカンマでキーワードを分割
            foreach ($keywords as $word) {
                $query->where(function ($q) use ($word) {
                    $q->where('title', 'LIKE', "%{$word}%")
                      ->orWhere('tag', 'LIKE', "%{$word}%")
                      ->orWhere('comment', 'LIKE', "%{$word}%");
                });
            }
        }
        
        $posts = $query->get();
        
        // クエリの内容をログに出力
        Log::info('検索キーワード:', ['keyword' => $keyword]);
        Log::info('生成されたクエリ:', ['query' => $query->toSql()]);
        
        return view('first.result', compact('posts', 'keyword'));
    }


    public function update(PostRequest $request, Post $post)
    {
        $input = $request->input('post');
        $input['user_id'] = $request->user()->id;
        $post->fill($input)->save();
        return redirect()->route('posts', ['post' => $post->id]);
    }

    public function delete(Post $post)
    {
        $post->delete();
        return redirect()->route('home')->with(['posts' => $post]);
    }
    
    public function myPosts()
    {
        $userId = Auth::id();
        $posts = Post::userPosts($userId);
        return view('dashboard', ['posts' => $posts]);
    }
}
