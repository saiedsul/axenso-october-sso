<?php namespace Axen\Sso\Components;

use Axen\Sso\Classes\AxenSso;
use Carbon\Carbon;
use Axen\Sso\Classes\Sso;
use Axen\Sso\Models\User;
use Cms\Classes\ComponentBase;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cookie;
use October\Rain\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Axen\Sso\Models\Log as Axenlog;
class Login extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name'        => 'SSO Login Form',
            'description' => 'Adds login form to page'
        ];
    }

    public function defineProperties()
    {
        return [];
    }
    public function onLogin() {
        $email = Input::get('email');
        $password = Input::get('password');
        $backURL = Input::get('backURL');
         $messages = [
             'email.required' => trans('axen.october-sso::lang.messages.validation.required.email'),
             'password.required' => trans('axen.october-sso::lang.messages.validation.required.password'),
             ];
        $validator = Validator::make(
         [
             'email' => $email,
             'password' => $password,
         ],
         [
             'email' => 'required|email',
             'password' => 'required',

         ],$messages
     );

     if ($validator->fails()){
        return ['#errors'=> $this->renderPartial('Login::errors',[
            'errorMsgs' =>$validator->messages()->all()
        ])];
    }
         else {
             //   $dardy = new Sso();
                $sso = new AxenSso();

                $response = $sso->login($email,$password);

                if ($response->getStatusCode() == 200) {
                    $sso_user = $response->object()->user;
                    $hasConsent = $response->object()->has_consent;
                    $dbUser = User::where('sso_id',$sso_user->id)->first();
                    if ($dbUser) {
                        $dbUser->first_name = $sso_user->profile->first_name;
                        $dbUser->last_name = $sso_user->profile->last_name;
                        $dbUser->email = $sso_user->email;
                        $dbUser->password = Hash::make(Input::get('password'));
                        $dbUser->privacy_consent = $hasConsent ? 1 : 0;
                        $dbUser->profile = $sso_user->profile;
                        $dbUser->enabled = $sso_user->active;
                        $dbUser->save();
                    }
                    else {
                        $dbUser = User::create([
                            'sso_id' => $sso_user->id,
                            'first_name' => $sso_user->profile->first_name,
                            'last_name' => $sso_user->profile->last_name,
                            'email' => $sso_user->email,
                            'password' => Hash::make(Input::get('password')),
                            'privacy_consent' => $hasConsent ? 1 : 0,
                            'enabled'=> $sso_user->active,
                            'profile' => $sso_user->profile,
                        ]);
                    }
                    $log = Axenlog::create([
                        'email' => $sso_user->email,
                        'action_type' => Axenlog::LOGIN,
                        'action_time' => Carbon::now()->format('Y-m-d H:i:s'),
                        'user_name' => $sso_user->profile->first_name,
                        'user_lastname' => $sso_user->profile->last_name,
                        'sso_id' =>  $sso_user->id,

                    ]);
                    $cookie = Cookie::make('user_id',$dbUser->sso_id,4320);
                    Cookie::queue($cookie);
                    if (!$hasConsent) {
                        return Redirect::to('/consent');
                    }
                    else {
                        if ($backURL != null) {
                            return Redirect::to($backURL);
                        }
                        else {
                            return Redirect::to('/');
                        }
                    }
                }
                else {
                    if ($response->getStatusCode() == 404) {
                        return ['#errors'=> $this->renderPartial('Login::errors/user-not-found')];
                    }
                    if ($response->getStatusCode() == 401) {
                        return ['#errors'=> $this->renderPartial('Login::errors/login-incorrect')];
                    }
                    else if ($response->getStatusCode() == 403) {
                        if ($response->object()->error == 'not-verified') {
                            return ['#errors'=> $this->renderPartial('Login::errors/user-unverfied')];
                        }
                        if ($response->object()->error == 'not-active') {
                            return ['#errors'=> $this->renderPartial('Login::errors/user-inactive')];
                        }
                    }
                    else {
                        return ['#errors'=> $this->renderPartial('Login::errors/generic')];
                    }
                }

         }

     }

}
