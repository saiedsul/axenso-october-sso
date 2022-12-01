<?php namespace Axen\Sso\Components;

use Axen\Sso\Classes\AxenSso;
use Axen\Sso\Classes\Sso;
use Axen\Sso\Models\User;
use Cms\Classes\ComponentBase;
use October\Rain\Support\Facades\Input;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Redirect;

class Consent extends ComponentBase
{
    public $user;

    public function componentDetails()
    {
        return [
            'name'        => 'Privacy Consent',
            'description' => 'Show Consent form for the user'
        ];
    }
    public function onRun()
    {
        $cookie = Cookie::get('user_id');
        if ($cookie) {
            $user = User::where('sso_id',(int)$cookie)->first();
            if ($user) {
                $this->user = $user;
            }
            else  {
                return Redirect::to('/login');
            }
        }
        else {
            return Redirect::to('/login');
        }



    }
    public function onConsent() {
        $cookie = Cookie::get('user_id');
        if ($cookie) {
            $user = User::where('sso_id',(int)$cookie)->first();
            if (!$user) {
                return Redirect::to('/login');
            }
        }
        else {
            return Redirect::to('/login');
        }


        $sso = new AxenSso();
        $response= $sso->consent($user->sso_id);
        if ($response->getStatusCode() == 200) {
            $user->privacy_consent = 1;
            $user->save();
            return Redirect::to('/');
        }
        else {
            return ['#errors'=> $this->renderPartial('Consent::errors/generic')];
        }

    }
    public function defineProperties()
    {
        return [];
    }
}
