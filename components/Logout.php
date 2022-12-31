<?php namespace Axen\Sso\Components;

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
        $this->addJs('/plugins/axen/sso/assets/js/sso.js',[
            'type' => "text/javascript",
        ]);
        $this->addJs('https://kit.fontawesome.com/6d76af8390.js',[
            'type' => "text/javascript",
        ]);
        $this->addCss('/plugins/axen/sso/assets/css/sso.css');
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
