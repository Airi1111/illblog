<?
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Auth;

class LikeController extends Controller
{
    public function like(Request $request)
    {
        $post = Post::find($request->id);
        $post->likes()->create(['user_id' => Auth::id()]);

        return response()->json(['count' => $post->likes->count()]);
    }

    public function unlike(Request $request)
    {
        $post = Post::find($request->id);
        $post->likes()->where('user_id', Auth::id())->delete();

        return response()->json(['count' => $post->likes->count()]);
    }
}
