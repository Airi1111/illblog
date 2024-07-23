<head>
    <link rel="stylesheet" href="{{ asset('/css/home.css')  }}" >
</head>

    <x-app-layout>

 
     <x-slot name="header">
     <a href='first/create'>投稿</a>
    </x-slot>

        <div class='home'>
           
            <h3 class="font-semibold leading-tight" >MY WORKS</h3>
           <div class="works">
                <div class="item">
                    @foreach($posts as $post)
                        <div class="content">
                            <div>
                                <img src="{{ $post->image_url }}" alt="画像が読み込めません。">
                            </div>
                            <div class="title">
                                <a href="{{ route('posts', ['post' => $post->id]) }}">{{ $post->title }}</a>
                            </div>
                            <div>
                                @if($post->is_liked_by_auth_user())
                                    <a href="{{ route('post.unlike', ['id' => $post->id]) }}" class="btn btn-success btn-sm"><ion-icon name="heart"></ion-icon><span class="badge">{{ $post->likes->count() }}</span></a>
                                @else
                                    <a href="{{ route('post.like', ['id' => $post->id]) }}" class="btn btn-secondary btn-sm"><ion-icon name="heart-outline"></ion-icon><span class="badge">{{ $post->likes->count() }}</span></a>
                                @endif
                            </div>
                            <small>{{ $post->user->name }}</small>
                        </div>
                    @endforeach
                </div>
            <div class="more">
                <a href="{{ route('pickup') }}" class="pickmore" type="button">もっと見る</a>
            </div>
        </div>
            <table class="questions">
            <h3 class="font-semibold leading-tight">MY QUESTIONS</h3>
            <div class='question'>
                    <a href="posts/questions"></a>
               
                 <div class="pickmore">
                     <a href="#" class="more" type="button">もっと見る</a>
                 </div>
            </div>    
            </table>    
            <div class="py-12">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900">
                            {{ Auth::user()->name }}{{ __("：ログイン中...") }}
        
                        </div>
                    </div>
                </div>
            </div>  
        </div>
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    </x-app-layout>
