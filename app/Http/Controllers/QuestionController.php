<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Question;
use App\Http\Requests\PostRequest;
//use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Like;
//use Cloudinary;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Log;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class QuestionController extends Controller
{
    public function indexQuestion(Request $request)
    {
        $questions = Question::orderBy('created_at', 'desc')->limit(6)->get();
        return view('first.index', compact('questions'));
    }

}
