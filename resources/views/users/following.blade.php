<x-app-layout>
    <div class="following-container">
        <h1>フォロー中リスト</h1>
        <ul class="user-list">
            @foreach($following as $followed)
                <li class="user-item">
                    <a href="{{ route('profile', $followed->id) }}">
                        <img src="{{ $followed->profile_image }}" alt="{{ $followed->name }} のプロフィール画像" class="user-image">
                        <span class="user-name">{{ $followed->name }}</span>
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
</x-app-layout>
