<?php
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\QuestionCommentsController;
use App\Http\Controllers\PostCommentController;
use App\Http\Controllers\FollowController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::middleware(['auth'])->group(function () {
    Route::get('/', [PostController::class, 'home'])->name('home');
    Route::get('/index', [PostController::class, 'index'])->name('index');
    Route::get('/index/search', [PostController::class, 'searchForm'])->name('searchForm');
    Route::get('/result', [PostController::class, 'search'])->name('result');
    Route::get('/first/create', [PostController::class, 'create'])->name('create');
    Route::post('/first', [PostController::class, 'store'])->name('store');
    Route::get('/first/like/{id}', [PostController::class, 'like'])->name('post.like');
    Route::get('/first/unlike/{id}', [PostController::class, 'unlike'])->name('post.unlike');
    Route::get('/first/pickup', [PostController::class, 'pickup'])->name('pickup');
    Route::get('/first/myworks', [PostController::class, 'myworks'])->name('myworks');
    Route::get('/first/{post}', [PostController::class, 'posts'])->name('posts');
    Route::put('/first/{post}', [PostController::class, 'update'])->name('update');
    Route::delete('/first/{post}', [PostController::class, 'delete'])->name('delete');
    Route::get('/first/{post}/edit', [PostController::class, 'edit'])->name('edit');
    Route::get('/users/profile', [PostController::class, 'profile'])->name('profile');
    

    Route::post('/first/{post}/comments', [PostCommentController::class, 'store'])->name('postcomments.store');
    Route::delete('/first/comments/{comment}', [PostCommentController::class, 'destroy'])->name('postcomments.destroy');

    
    Route::post('/question/{question}/comments', [QuestionCommentsController::class, 'store'])->name('questioncomments.store');
    Route::delete('/question/comments/{comment}', [QuestionCommentsController::class, 'destroy'])->name('questioncomments.destroy');

    Route::get('/index/question', [QuestionController::class, 'questionpick'])->name('pick.questions');

    Route::get('/question/create', [QuestionController::class, 'create'])->name('question.create');
    Route::post('/question', [QuestionController::class, 'store'])->name('question.store');
    Route::get('/question/myquestion', [PostController::class, 'myquestions'])->name('myquestions');
    Route::get('/question/{question}', [QuestionController::class, 'show'])->name('question.show');
    Route::put('/question/{question}', [QuestionController::class, 'update'])->name('question.update');
    Route::delete('/question/{question}', [QuestionController::class, 'delete'])->name('question.delete');
    Route::get('/question/{question}/edit', [QuestionController::class, 'edit'])->name('question.edit');
    
    // ProfileController のルート定義
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    Route::get('/users/{user}', [ProfileController::class, 'show'])->name('user.profile');
    Route::get('/users/{user}/posts', [PostController::class, 'userPosts'])->name('user.posts');
    Route::get('/users/{user}/followers', [ProfileController::class, 'followers'])->name('user.followers');

    
    Route::post('/follow/{user}', [FollowController::class, 'follow'])->name('follow');
    Route::post('/unfollow/{user}', [FollowController::class, 'unfollow'])->name('unfollow');
    Route::get('/followers', [FollowController::class, 'followers'])->name('followers');
    Route::get('/following', [FollowController::class, 'following'])->name('following');
        
});

// 認証関連のルート
require __DIR__.'/auth.php';
