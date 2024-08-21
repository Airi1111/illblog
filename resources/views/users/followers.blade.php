<x-app-layout>
    <div class="followers-container">
        <h1>フォロワーリスト</h1>
        <ul class="user-list">
            @foreach($followers as $follower)
                <li class="user-item">
                    <a href="{{ route('profile', $follower->id) }}">
                        <img src="{{ $follower->profile_image }}" alt="{{ $follower->name }} のプロフィール画像" class="user-image">
                        <span class="user-name">{{ $follower->name }}</span>
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
</x-app-layout>
