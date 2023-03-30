<?php namespace Axen\Sso\Components;

use Carbon\Carbon;
use Axen\Sso\Classes\Sso;
use Axen\Sso\Models\User;
use Axen\Sso\Classes\AxenSso;
use Axen\Sso\Models\Settings;
use Cms\Classes\ComponentBase;
use Axen\Sso\Models\Log as Axenlog;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cookie;
use October\Rain\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class Login extends ComponentBase
{
    public $logo;
    public $redirect;
    public function componentDetails()
    {

        return [
            'name'        => 'SSO Login Form',
            'description' => 'Adds login form to page'
        ];
    }
    public function onRun() {
        $this->addJs('/plugins/axen/sso/assets/js/sso.js',[
            'type' => "text/javascript",
        ]);
        $this->addJs('https://kit.fontawesome.com/6d76af8390.js',[
            'type' => "text/javascript",
        ]);
        $this->addCss('/plugins/axen/sso/assets/css/sso.css');
        $settings = Settings::instance();
        $this->logo = $settings->logo;
        $this->redirect = '/';
        if (@$settings->redirect_after_login_consent != null) {
            $this->redirect = $settings->redirect_after_login_consent;
        }
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
             'email.required' => trans('axen.sso::lang.messages.validation.required.email'),
             'password.required' => trans('axen.sso::lang.messages.validation.required.password'),
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
                $sso = new AxenSso();
                $response = $sso->login($email,$password);
                if ($response->getStatusCode() == 200) {
                    $sso_user = $response->object()->user;
                    $hasConsent = $response->object()->has_consent;
                    $must_update_profile = $response->object()->user->must_update_profile;
                    $dbUser = User::where('sso_id',$sso_user->id)->first();
                    if ($dbUser) {
                        $dbUser->first_name = $sso_user->profile->first_name;
                        $dbUser->last_name = $sso_user->profile->last_name;
                        $dbUser->email = $sso_user->email;
                        $dbUser->password = Hash::make(Input::get('password'));
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
                            'enabled'=> $sso_user->active,
                            'profile' => $sso_user->profile,
                            'privacy_consent' => $hasConsent,
                            'profile_updated' => !$must_update_profile,
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
                    if ($must_update_profile == true) {
                        return Redirect::to('/profile');
                    }
                    else {
                        if (!$hasConsent) {
                            return Redirect::to('/consent');
                        }
                        else {
                            if ($backURL != null) {
                                return Redirect::to($backURL);
                            }
                            else {
                                return Redirect::to($this->redirect);
                            }
                        }
                    }

                }
                else {
                    if ($response->getStatusCode() == 404) {
                        return ['#errors'=> $this->renderPartial('Login::errors',[
                            'errorMsgs' => [trans('axen.sso::lang.messages.login.email-not-found')]
                            ])];
                    }
                    if ($response->getStatusCode() == 401) {

                        return ['#errors'=> $this->renderPartial('Login::errors',[
                            'errorMsgs' => [trans('axen.sso::lang.messages.login.incorrect-login')]
                            ])];
                    }
                    else if ($response->getStatusCode() == 403) {
                        if ($response->object()->error == 'not-verified') {
                            return ['#errors'=> $this->renderPartial('Login::errors',[
                                'errorMsgs' => [trans('axen.sso::lang.messages.login.user-unverfied')]
                                ])];
                        }
                        if ($response->object()->error == 'not-active') {
                            return ['#errors'=> $this->renderPartial('Login::errors',[
                                'errorMsgs' => [trans('axen.sso::lang.messages.login.user-inactive')]
                                ])];
                        }
                    }
                    else {
                        return ['#errors'=> $this->renderPartial('Login::errors',[
                            'errorMsgs' => [trans('axen.sso::lang.messages.generic')]
                            ])];
                    }
                }

         }

     }

}
