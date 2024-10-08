<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionComment extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'comment',
        'question_id',
        'user_id',
    ];

    
    public function question()
    {
        return $this->belongsTo(Question::class, 'question_id');
    }


    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
