<?php
namespace App\Http\Controllers;

use App\Http\Requests\PostRequest;
use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class QuestionController extends Controller
{
    public function index(questions $question)
    {
        $questions = Post::where('type', 'question')
                         ->withCount('likes')
                         ->orderBy('likes_count', 'desc')
                         ->paginate(6);

        foreach ($questions as $question) {
            $question->image_url = json_decode($question->image_urls, true)[0] ?? null;
        }

        return view('question.questions', ['questions' => $questions]);
    }

    public function posts(Post $post)
    {
        return view('question.posts', ['post' => $post]);
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
        return view('question.create');
    }

    public function store(PostRequest $request, Post $post)
    {
        $input = $request->input('post');
        $input['user_id'] = Auth::id();
        $input['type'] = 'question';
        
        if ($request->hasFile('post.images')) {
            $imageUrls = [];
            $images = $request->file('post.images');
            foreach ($images as $image) {
                try {
                    // Cloudinary に画像をアップロードし、リサイズと圧縮を適用
                    $uploadedFileUrl = Cloudinary::upload($image->getRealPath(), [
                        'folder' => 'dgougzdd8', // フォルダ名を指定
                        'transformation' => [
                            'width' => 800, // 画像の幅を800pxにリサイズ
                            'quality' => 'auto', // 画像の品質を自動で最適化
                            'fetch_format' => 'auto' // 最適なフォーマットに変換
                        ]
                    ])->getSecurePath();
                    $imageUrls[] = $uploadedFileUrl;
                } catch (\Exception $e) {
                    return back()->with('error', 'Failed to upload image: ' . $e->getMessage());
                }
            }
            $input['image_urls'] = json_encode($imageUrls); // JSON形式で保存
        }
        $post->fill($input)->save();
        return redirect()->route('questions.show', ['question' => $post->id]);
    }

    public function searchForm()
    {
        return view('question.search');
    }

    public function search(Request $request)
    {
        $keyword = $request->input('keyword');
        
        $query = Post::where('type', 'question');
        
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
        
        return view('question.result', compact('posts', 'keyword'));
    }

    public function update(PostRequest $request, Post $post)
    {
        $input = $request->input('post');
        $input['user_id'] = $request->user()->id;
        $post->fill($input)->save();
        return redirect()->route('questions.show', ['question' => $post->id]);
    }

    public function delete(Post $post)
    {
        $post->delete();
        return redirect()->route('questions.index');
    }
    
    public function myPosts()
    {
        $userId = Auth::id();
        $posts = Post::where('user_id', $userId)->where('type', 'question')->get();
        return view('question.myworks', ['posts' => $posts]);
    }
  
    public function toggleLike($id, $action)
    {
        $post = Post::findOrFail($id);
    
        if ($action === 'like' && !$post->is_liked_by_auth_user()) {
            Like::create([
                'post_id' => $id,
                'user_id' => Auth::id(),
            ]);
        } elseif ($action === 'unlike' && $post->is_liked_by_auth_user()) {
            $like = Like::where('post_id', $id)->where('user_id', Auth::id())->first();
            if ($like) {
                $like->delete();
            }
        }
    
        $likesCount = $post->likes->count();
        $isLiked = $post->is_liked_by_auth_user();
    
        $likesHtml = view('partials.likes', compact('likesCount', 'isLiked', 'post'))->render();
    
        return response()->json(['likesHtml' => $likesHtml]);
    }
}
