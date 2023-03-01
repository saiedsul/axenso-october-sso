<?php namespace Axen\Sso\Components;

use Axen\Sso\Classes\Sso;
use Axen\Sso\Models\User;
use Axen\Sso\Classes\AxenSso;
use Axen\Sso\Classes\Helpers;
use Axen\Sso\Models\Settings;
use Cms\Classes\ComponentBase;
use Illuminate\Support\Facades\Cookie;
use October\Rain\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;

class Consent extends ComponentBase
{
    public $user;
    public $logo;
    public $privacy_text;
    public $privacy_policy_url;
    public function componentDetails()
    {
        return [
            'name'        => 'Privacy Consent',
            'description' => 'Show Consent form for the user'
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
        $settings = Settings::instance();
        $this->privacy_policy_url = $settings->privacy_policy_url;
        $this->privacy_text = $settings->privacy_text;
        $this->logo = $settings->logo;
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
        $response= $sso->consent($user->email);
        if ($response->getStatusCode() == 200 || $response->getStatusCode() == 201) {
            $user->privacy_consent = 1;
            $user->save();
            return Redirect::to('/');
        }
        else {
            return ['#errors'=> $this->renderPartial('Consent::errors',[
                'errorMsgs' => [trans('axen.sso::lang.messages.generic')]
                ])];
        }
    }
    public function defineProperties()
    {
        return [];
    }
}
