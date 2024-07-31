<?php

namespace App\View\Components;

use Illuminate\View\Component;

class MyProfileImage extends Component
{
    public $imageUrl;

    public function __construct($imageUrl = null)
    {
        $this->imageUrl = $imageUrl;
    }

    public function render()
    {
        return view('components.profile-image');
    }
}
