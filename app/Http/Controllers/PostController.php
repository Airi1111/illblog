<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostRequest;
use Illuminate\Http\Request;
use App\Models\Post;
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
         $query = Post::withCount('likes')
                     ->orderBy('likes_count', 'desc');

        // ページネーションを実行
        $posts = (new Post)->getPaginateByLimit($query);
        //$posts = $post->getPaginateByLimit();
        foreach ($posts as $post) {
            $post->image_url = json_decode($post->image_urls, true)[0] ?? null;
        }
        
        /*$tags = Tag::pluck('name');
        return response()->json($tags);*/
        return view('first.index', ['posts' => $posts]);
    }

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

    public function posts(Post $post)
    {
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
                $input['image_urls'] = json_encode($imageUrls);
            } catch (\Exception $e) {
                return back()->with('error', $e->getMessage());
            }
        }
    
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
