<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;


class Question extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_id',
        'category_id',
        'title', 
        'comment', 
        'image_urls'
    ];
     public function user()
    {
        return $this->belongsTo(User::class);
    }

     public function getPaginateByLimit($query, int $limit_count = 6)
    {
        return $query->with('user')->orderBy('updated_at', 'DESC')->paginate($limit_count);
    }

    public function allView(){
        return $this::with('user')->orderBy('updated_at', 'DESC')->get();
    }
    
     public function likes()
    {
        return $this->hasMany(LikeQuestion::class, 'question_id');
    }

    /**
     * 認証ユーザーがこの投稿にLIKEを付けているかを判定する
     *
     * @return bool true:LIKEがついている false:LIKEがついていない
     */
    public function is_liked_by_auth_user()
    {
        $id = Auth::id();
    
        $likers = [];
        if ($this->like_questions) { // like_questionsがnullでないかを確認
            foreach($this->like_questions as $like) {
                $likers[] = $like->user_id;
            }
        }
    
        return in_array($id, $likers);
    }

    
    public function userPosts($userId)
    {
        return $this::where('user_id', $userId)->orderBy('updated_at', 'DESC')->get();
    }
    
    public function comments()
    {
        return $this->hasMany(QuestionComment::class, 'question_id');
    }

    
}
