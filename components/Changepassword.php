<?php namespace Axenso\Sso\Components;

use Axenso\Sso\Classes\AxensoSso;
use Axenso\Sso\Classes\Sso;
use Cms\Classes\ComponentBase;
use Illuminate\Support\Facades\Log;
use October\Rain\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class Changepassword extends ComponentBase
{
    public $code;


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

            $sso = new AxensoSso();
            $response = $sso->resetPassword($this->code,Input::get('password'),Input::get('password_confirmation'));
            if ($response->getStatusCode() == 404) {
                return ['#errors'=> $this->renderPartial('Changepassword::errors',[
                    'errorMsgs' => [trans('axenso.october-sso::lang.messages.reset_password.invalid_link')]
                    ])];
            }
            else if ($response->getStatusCode() == 201) {
                return  Redirect::to('/login');
            }
            else {
                return ['#errors'=> $this->renderPartial('Changepassword::errors',[
                    'errorMsgs' => [trans('axenso.october-sso::lang.messages.generic')]
                    ])];
               }
        }




     }
}
