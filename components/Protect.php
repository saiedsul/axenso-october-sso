<?php namespace Axen\Sso\Components;

use Axen\Sso\Models\User;
use Cms\Classes\ComponentBase;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Redirect;

class Protect extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name'        => 'Add Auth to page',
            'description' => 'Make page available for logged in users'
        ];
    }
    public function onRun(){
        $this->addJs('/plugins/axen/sso/assets/js/sso.js',[
            'type' => "text/javascript",
        ]);
        $this->addCss('/plugins/axen/sso/assets/css/sso.css');
        $this->page['consested'] = 0;
        $cookie = Cookie::get('user_id');
        if ($cookie) {
            $user = User::where('sso_id',(int)$cookie)->first();
            if ($user) {
                if ($user->privacy_consent) {
                    $this->page['consested'] = 1;
                }
                if ($user->profile_udated) {
                    $this->page['profile_udated'] = 1;
                }
                if ($user->profile_updated == 0) {
                    return Redirect::to('/profile');
                }
                else if ($user->privacy_consent == 0) {
                    return Redirect::to('/consent');
                }
            }
            else {
                return Redirect::to('/login');
            }
        }
        else {
            return Redirect::to('/login');
        }

    }

    public function defineProperties()
    {
        return [];
    }
}
