<?php namespace Axen\Sso\Components;

use Axen\Sso\Models\User;
use Cms\Classes\ComponentBase;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Redirect;

class Registerstatus extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name'        => 'Get Auth Details',
            'description' => 'add params to the page (isLogged) (consested) as booleans'
        ];
    }

    public function onRun(){
        $this->addJs('/plugins/axen/sso/assets/js/sso.js',[
            'type' => "text/javascript",
        ]);
        $this->addJs('https://kit.fontawesome.com/6d76af8390.js',[
            'type' => "text/javascript",
        ]);
        $user = null;
        $cookie = Cookie::get('user_id');

        if ($cookie) {
            $user = User::where('sso_id',(int)$cookie)->first();
            $this->page['isLogged'] = 1;
            $this->page['sso_user'] = $user;
        }
        else {
            $this->page['isLogged'] = 0;
        }
        $this->page['consested'] = 0;
        if ($user != null)  {
            if ($user->privacy_consent) {
                $this->page['consested'] = 1;
            }
        }
    }
    public function defineProperties()
    {
        return [];
    }
}
