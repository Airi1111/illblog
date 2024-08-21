<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use App\Models\User; 


class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user = $request->user();
        $isFollowing = Auth::user()->isFollowing($user); // フォローしているかどうかをチェック
    
        return view('profile.edit', [
            'user' => $user,
            'isFollowing' => $isFollowing,
        ]);
    }


    /**
     * Update the user's profile information.
     */
    // app/Http/Controllers/ProfileController.php

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        
        // ユーザー情報の更新
        $user->fill($request->validated());
    
        if ($request->user()->isDirty('email')) {
            $user->email_verified_at = null;
        }
    
        // プロフィール画像のアップロード処理
        if ($request->hasFile('profile_image')) {
            // 画像をCloudinaryにアップロード
            $imageUrl = Cloudinary::upload($request->file('profile_image')->getRealPath(), [
                'folder' => 'profile_images',
                'transformation' => [
                    'width' => 200,
                    'height' => 200,
                    'crop' => 'thumb',
                    'gravity' => 'face',
                ],
            ])->getSecurePath();
    
            // 既存の画像を削除
            if ($user->profile_image) {
                $publicId = basename($user->profile_image, '.jpg'); // 必要に応じて拡張子を変更
                Cloudinary::destroy($publicId);
            }
    
            $user->profile_image = $imageUrl;
        }
    
        $user->save();
    
        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }


    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current-password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
    
    public function show(User $user)
    {
        return view('users.profile', compact('user'));
    }
    // ProfileController.php
    public function followers(User $user)
    {
        $followers = $user->followers; // ユーザーのフォロワーを取得
    
        return view('users.profile', compact('user', 'followers'));
    }

}
