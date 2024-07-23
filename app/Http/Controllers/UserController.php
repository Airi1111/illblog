<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(User $user)
    {
        return view('dashboard')->with(['own_posts' => $user->getOwnPaginateByLimit()]);
    }
}
