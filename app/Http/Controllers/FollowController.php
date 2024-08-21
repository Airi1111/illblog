<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Notifications\FollowedNotification;

class FollowController extends Controller
{
    public function follow(User $user)
    {
        // 自分自身をフォローできないようにする
        if (auth()->user()->id === $user->id) {
            return redirect()->back()->with('error', '自分自身をフォローすることはできません');
        }
        
        // すでにフォローしているか確認
        if (auth()->user()->isFollowing($user)) {
            return redirect()->back()->with('info', '既にフォローしています');
        }
    
        auth()->user()->follow($user);
    
        // フォローされたユーザーに通知を送信
        $user->notify(new FollowedNotification(auth()->user()));
    
        return redirect()->back()->with('success', 'フォローしました');
    }

    
    public function unfollow(User $user)
    {
        // 自分自身をフォローできないようにする
        if (auth()->user()->id === $user->id) {
            return redirect()->back()->with('error', '自分自身をフォローすることはできません');
        }
    
        auth()->user()->unfollow($user);

        return redirect()->back()->with('success', 'フォローを解除しました');
    }
    
    public function followers()
    {
        $followers = auth()->user()->followers;
        return view('users.followers', compact('followers'));
    }

    public function following()
    {
        $following = auth()->user()->following;
        return view('users.following', compact('following'));
    }


}
