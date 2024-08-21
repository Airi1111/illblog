<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('/css/profile.css') }}">
    <title>プロフィール</title>
</head>

<x-app-layout>
    <div class="profile-container">
        
        <!-- プロフィールヘッダー -->
        <div class="profile-header">
            <div class="profile-image-container">
                <img src="{{ $user->profile_image }}" alt="プロフィール画像" class="profile-image">
            </div>
            <div class="profile-info">
                <h1 class="profile-name">
                    <a href="{{ route('user.profile', $user) }}">{{ $user->name }}</a>
                </h1>
                <p class="profile-bio">{{ $user->bio }}</p>
                <div class="profile-actions">
                    @if (auth()->user()->id === $user->id)
                        <!-- 自分自身のプロフィールページにはフォローボタンを表示しない -->
                    @elseif (auth()->user()->isFollowing($user))
                        <form action="{{ route('unfollow', $user) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-secondary">フォロー中</button>
                        </form>
                    @else
                        <form action="{{ route('follow', $user) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-primary">フォローする</button>
                        </form>
                    @endif
                </div>
                <div class="profile-links">
                    <a href="{{ route('followers') }}" class="profile-link">フォロワー</a>
                    <a href="{{ route('following') }}" class="profile-link">フォロー中</a>
                </div>
            </div>
        </div>
        
    </div>
</x-app-layout>
