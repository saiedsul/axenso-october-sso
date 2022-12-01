<?php namespace Axen\Sso\Components;

use Axen\Sso\Classes\Sso;
use Cms\Classes\ComponentBase;
use Axen\Sso\Classes\AxenSso;
use October\Rain\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class Resetpassword extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name'        => 'Passowrd Reset Component',
            'description' => 'Add form to request resetting password'
        ];
    }

    public function defineProperties()
    {
        return [];
    }

    public function onReset() {
        $email = Input::get('email');
        $messages = ['email.required' => 'Il campo Email è richiesto.'];
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
                return Redirect::to('/recupera-password/success');
            }
            if ($response->getStatusCode() == 404) {
               return ['#errors'=> $this->renderPartial('Resetpassword::errors',[
                    'errorMsgs' => ["Ops, l'indirizzo indicato non è presente, ti invitiamo a <a href='/register'>registrarti</a> come nuovo utente"]
                    ])];
            }
            else {
                return ['#errors'=> $this->renderPartial('Resetpassword::errors',[
                    'errorMsgs' => ['something went wrong, please try again later']
                    ])];
            }
        }
     }
}
