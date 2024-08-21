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
use App\Models\User; 


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
    
        // ビューにデータを渡す
        return view('dashboard', [
            'posts' => $posts,
            'questions' => $questions,
            'user' => Auth::user()  // ユーザー情報をビューに渡す
        ]);
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
        $post = Post::findOrFail($id);
        $user = Auth::user();
    
        // すでに「いいね」しているかを確認
        $liked = $post->likes()->where('user_id', $user->id)->exists();
        if (!$liked) {
            $post->likes()->create(['user_id' => $user->id]);
        }
    
        // 「いいね」の数を返す
        return response()->json(['count' => $post->likes->count(), 'liked' => !$liked]);
    }
    
    public function unlike($id)
    {
        $post = Post::findOrFail($id);
        $user = Auth::user();
    
        // 「いいね」を解除する
        $liked = $post->likes()->where('user_id', $user->id)->exists();
        if ($liked) {
            $post->likes()->where('user_id', $user->id)->delete();
        }
    
        // 「いいね」の数を返す
        return response()->json(['count' => $post->likes->count(), 'liked' => false]);
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

    public function profile()
    {
        $user = Auth::user(); // 現在のユーザーを取得
        $posts = Post::where('user_id', $user->id)->get(); // ユーザーの投稿を取得
        return view('users.show', [
            'posts' => $posts,
            'user' => $user // ビューにユーザー情報を渡す
        ]);
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
        // 認可チェック
        if (Auth::id() !== $post->user_id) {
            return redirect()->route('home')->with('error', 'あなたにはこの投稿を削除する権限がありません。');
        }
    
        $post->delete();
        return redirect()->route('home')->with('status', '投稿が削除されました。');
    }

    
    public function myPosts()
    {
        $userId = Auth::id();
        $posts = Post::userPosts($userId);
        return view('dashboard', ['posts' => $posts]);
    }
    
    public function userPosts(User $user)
    {
        $posts = $user->posts()->paginate(10); // ユーザーの投稿
        $questions = $user->questions()->paginate(10); // ユーザーの質問
    
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
    
        // ビューにデータを渡す
        return view('dashboard', [
            'posts' => $posts,
            'questions' => $questions,
            'user' => Auth::user()  // ユーザー情報をビューに渡す
        ]);
        return view('users.posts', [
            'user' => $user,
            'posts' => $posts,
            'questions' => $questions
        ]);
    }

}
