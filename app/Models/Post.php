<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Post extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'title',
        'tag',
        'comment',
        'image_urls',  // 変更: image_url -> image_urls
        'user_id',
    ];
    
    public function user(){
        return $this->belongsTo(User::class);
    }
    
    public function getPaginateByLimit(int $limit_count = 6)
    {
        return $this::with('user')->orderBy('updated_at', 'DESC')->paginate($limit_count);
    }

    public function allView(){
        return $this::with('user')->orderBy('updated_at', 'DESC')->get();
    }
    
    public function likes()
    {
        return $this->hasMany(Like::class, 'post_id');
    }

    /**
     * 認証ユーザーがこの投稿にLIKEを付けているかを判定する
     *
     * @return bool true:LIKEがついている false:LIKEがついていない
     */
    public function is_liked_by_auth_user()
    {
        $id = Auth::id();

        $likers = array();
        foreach($this->likes as $like) {
            array_push($likers, $like->user_id);
        }

        return in_array($id, $likers);
    }
    
    public function userPosts($userId)
    {
        return $this::where('user_id', $userId)->orderBy('updated_at', 'DESC')->get();
    }

}
