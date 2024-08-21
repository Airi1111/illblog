<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <title>Top</title>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" integrity="sha384-DyZ88mC6Up2uqS4h/KRgHuoeGwBcD4Ng9SiP4dIRy0EXTlnuz47vAwmeGwVChigm" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('/css/top.css') }}">
</head>
<body>
    <nav x-data="{ open: false, showNotifications: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <!-- Logo -->
                <div class="shrink-0">
                    <a href="{{ route('home') }}">
                        <img src="{{ asset('img/ep_menu.png') }}" alt="Logo" width="25" height="25">
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden sm:flex space-x-8">
                    <x-nav-link :href="route('home')" :active="request()->routeIs('home')">
                        <i class="fa-solid fa-house" style="color: #d54444;"></i>
                    </x-nav-link>
                    <x-nav-link :href="route('index')" :active="request()->routeIs('index')">
                        <i class="fa-solid fa-fire" style="color: #d54444;"></i>
                    </x-nav-link>
                    <x-nav-link :href="route('create')" :active="request()->routeIs('create')">
                        <i class="fa-solid fa-plus" style="color: #d54444;"></i>
                    </x-nav-link>
                    <x-nav-link :href="route('searchForm')" :active="request()->routeIs('searchForm')">
                        <i class="fa-solid fa-magnifying-glass" style="color: #d54444;"></i>
                    </x-nav-link>
                </div>

                 <div class="hidden sm:flex items-center">
                    <!-- 通知アイコン -->
                    <div class="relative flex items-center notification-icon">
                        <button @click="showNotifications = !showNotifications" class="focus:outline-none">
                            <i class="fa-regular fa-bell"></i>
                        </button>

                        <!-- 通知ドロップダウン -->
                        <div x-show="showNotifications" class="absolute right-0 mt-2 w-48 bg-white shadow-lg rounded-lg py-2">
                            @foreach (auth()->user()->notifications as $notification)
                                <div class="px-4 py-2 border-b border-gray-200">
                                    @if ($notification->type === 'App\Notifications\FollowedNotification')
                                        {{ $notification->data['follower_name'] }} has followed you.
                                    @elseif ($notification->type === 'App\Notifications\LikedNotification')
                                        {{ $notification->data['liker_name'] }} liked your post: "{{ $notification->data['post_title'] }}".
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- 設定ドロップダウン -->
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                <div>{{ Auth::user()->name }}</div>
                                <div class="ml-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>
                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')">
                                {{ __('Profile') }}
                            </x-dropdown-link>

                            <!-- ログアウトフォーム -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault();
                                                    this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>
            </div>
        </div>
    </nav>
    <script src="https://kit.fontawesome.com/fdd4f25b90.js" crossorigin="anonymous"></script>
</body>
</html>
