<?php namespace Axen\Sso\Components;

use Axen\Sso\Classes\Sso;
use Axen\Sso\Classes\AxenSso;
use Axen\Sso\Models\Settings;
use Cms\Classes\ComponentBase;
use Illuminate\Support\Facades\Log;
use October\Rain\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class Changepassword extends ComponentBase
{
    public $code;
    public $logo;

    public function componentDetails()
    {
        return [
            'name'        => 'Change Password Form Component',
            'description' => 'Allow User to change the password'
        ];
    }
    public function init() {
        $this->code = Input::get('code');

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
        if ($this->code == '' || $this->code == null) {
            return Redirect::to('/login');
        }
    }
    public function defineProperties()
    {
        return [];
    }

    public function onChange() {
        $password = Input::get('password');
        $password_confirmation = Input::get('password_confirmation');
        $vcode = Input::get('code');
        $messages = [
                'password.required' => 'Il campo password è richiesto.',
                'password.confirmed' => 'Conferma della password non valida' ,
                'code.required' => ''
        ];
        $validator = Validator::make(
            ['password' => $password , 'password_confirmation' => $password_confirmation ,'code' => $vcode],
            ['password' => 'required|confirmed', 'code' => 'required'
         ],$messages
        );
        if ($validator->fails()){
            return ['#errors'=> $this->renderPartial('Changepassword::errors',[
                'errorMsgs' =>$validator->messages()->all()
            ])];
        }
         else {
            $sso = new AxenSso();
            $response = $sso->resetPassword($this->code,Input::get('password'),Input::get('password_confirmation'));
            if ($response->getStatusCode() == 404) {
                return ['#errors'=> $this->renderPartial('Changepassword::errors',[
                    'errorMsgs' => [trans('axen.sso::lang.messages.reset_password.invalid_link')]
                    ])];
            }
            else if ($response->getStatusCode() == 201) {
                return  Redirect::to('/login');
            }
            else {
                return ['#errors'=> $this->renderPartial('Changepassword::errors',[
                    'errorMsgs' => [trans('axen.sso::lang.messages.generic')]
                    ])];
               }
        }




     }
}
