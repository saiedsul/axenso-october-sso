<?php namespace Axen\Sso\Components;

use Axen\Sso\Classes\Sso;
use Axen\Sso\Classes\AxenSso;
use Axen\Sso\Models\Settings;
use Cms\Classes\ComponentBase;
use October\Rain\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class Resetpassword extends ComponentBase
{
    public $logo;
    public function componentDetails()
    {
        return [
            'name'        => 'Passowrd Reset Component',
            'description' => 'Add form to request resetting password'
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
      }
    public function defineProperties()
    {
        return [];
    }

    public function onReset() {
        $settings = Settings::instance();
        $email = Input::get('email');
        $messages = ['email.required' => trans('axen.sso::lang.messages.resetpassword.emailrequired')];
        $validator = Validator::make(
         ['email' => $email],
         ['email' => 'required|email',
         ],$messages
        );
        if ($validator->fails()){
            return ['#errors'=> $this->renderPartial('Resetpassword::errors',[
                'errorMsgs' =>$validator->messages()->all()
            ])];
        }
         else {
            $sso = new AxenSso();
            $response = $sso->resetPasswordRequest(Input::get('email'));
            if ($response->getStatusCode() == 201) {
                return ['#main_container'=> $this->renderPartial('Resetpassword::success',[
                    'logo' => $settings->logo
                    ])];
            }
            if ($response->getStatusCode() == 404) {
               return ['#errors'=> $this->renderPartial('Resetpassword::errors',[
                    'errorMsgs' => [trans('axen.sso::lang.messages.resetpassword.email-not-found')]
                    ])];
            }
            else {
                return ['#errors'=> $this->renderPartial('Resetpassword::errors',[
                    'errorMsgs' => [trans('axen.sso::lang.messages.generic')]
                    ])];
            }
        }
     }
}
