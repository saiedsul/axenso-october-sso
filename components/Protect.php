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
        $this->page['consested'] = 0;
        $cookie = Cookie::get('user_id');
        if ($cookie) {
            $user = User::where('sso_id',(int)$cookie)->first();
            if ($user) {
                if ($user->privacy_consent) {
                    $this->page['consested'] = 1;
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
