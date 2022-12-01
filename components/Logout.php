<?php namespace Axenso\Sso\Components;

use Cms\Classes\ComponentBase;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Redirect;

class Logout extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name'        => 'Logout Component',
            'description' => 'No description provided yet...'
        ];
    }

    public function onRun()
    {
        $cookie = Cookie::forever(
            'user_id',
            0
        );
        Cookie::queue($cookie);

         return Redirect::to('/');
    }


    public function defineProperties()
    {
        return [];
    }
}
