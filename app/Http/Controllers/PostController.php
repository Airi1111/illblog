<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostRequest;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Like;
use Cloudinary;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\SoftDeletes;

class PostController extends Controller
{
    public function index(Post $post, Request $request)
    {
        return view('first.index', ['posts' => $post->getPaginateByLimit()]);
    }

    public function home(Post $post)
    {
        return view('dashboard', ['posts' => $post->getPaginateByLimit()]);
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
    
        if ($request->hasFile('images')) {
            $imageUrls = [];
            foreach ($request->file('images') as $image) {
                try {
                    // Cloudinary に画像をアップロード
                    $image_url = Cloudinary::upload($image->getRealPath())->getSecurePath();
                    $imageUrls[] = $image_url;
                } catch (\Exception $e) {
                    return back()->with('error', 'Failed to upload image: ' . $e->getMessage());
                }
            }
            $input['image_urls'] = json_encode($imageUrls); // JSON 形式で保存
        }
    
        $post->fill($input)->save();
        return redirect()->route('posts', ['post' => $post->id]);
    }



    public function pickup(Post $post)
    {
        return view('first.pickup', ['posts' => $post->allView()]);
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
            $keywords = explode(' ', preg_replace('/\s+/', ' ', $keyword));
            foreach ($keywords as $word) {
                $query->where(function ($q) use ($word) {
                    $q->where('title', 'LIKE', "%{$word}%")
                      ->orWhere('tag', 'LIKE', "%{$word}%")
                      ->orWhere('comment', 'LIKE', "%{$word}%");
                });
            }
        }

        $posts = $query->get();

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
        return redirect()->route('home')->with(['posts' => $post->getPaginateByLimit()]);
    }
}
