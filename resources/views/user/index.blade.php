<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="UTF-8">
         <link rel="stylesheet" href="{{ asset('/css/home.css')  }}" >
        <title>index</title>
        <!--<style>
     
            header{
                background-color: #FFCACA;
                color: block;
            }
           .more{
               text-align: center;
           }
           .pickups,.question{
               
               margin:30px;
           }
           .posts{
               text-align: end;
               color:block;
               
           }
          
       
        </style>-->
  
    </head>
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('HOME') }}
        </h2>
    </x-slot>


    
    <table class="myworks">
        <br />
        <h4 class="font-semibold leading-tight">MY WORKS</h4>
            <div class="own_posts">
        @foreach($own_posts as $post)
            <div>
                <h4><a href="/posts/{{ $post->id }}">{{ $post->title }}</a></h4>
                <small>{{ $post->user->name }}</small>
                <p>{{ $post->comment }}</p>
            </div>
        @endforeach
        
        <div class='paginate'>
            {{ $own_posts->links() }}
        </div>
        <div class="more">
            <a href="pickup.blade.php" class="more" type="button">もっと見る</a>
        </div>
                            
    </table>
                        
    <table class="myquestions">
        <br />
        <h4 class="font-semibold leading-tight">MY QUESTIONS</h4>
        <div class='question'>
            <a href="posts/questions"></a>
            <div class="more">
                <a href="#" class="pickmore" type="button">もっと見る</a>
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
</x-app-layout>
