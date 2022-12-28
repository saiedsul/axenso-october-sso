<?php namespace Axen\Sso\Components;

use Axen\Sso\Classes\Sso;
use Axen\Sso\Models\User;
use Cms\Classes\ComponentBase;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cookie;
use October\Rain\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class Profile extends ComponentBase
{
    public $profissions;
    public $user;
    public $pspecs;
    public function componentDetails()
    {
        return [
            'name'        => 'SSO Profile Form',
            'description' => 'Render profile form'
        ];
    }
    public function onRun(){
        $this->addJs('/plugins/axen/sso/assets/js/sso.js',[
            'type' => "text/javascript",
        ]);
        $this->addCss('/plugins/axen/sso/assets/css/sso.css');
    }
    public function onRender() {

        $cookie = Cookie::get('user_id');
        $this->user =  User::where('sso_id',$cookie)->first();
        $this->getProfissions();

    }
    public function getProfissions() {
        $file = __DIR__ . '/../data/profissions.json';
        $profs = json_decode(file_get_contents($file), true);
        foreach ($profs as $key => $prof) {
            if ($profs[$key]['name'] == $this->user->profession) {
                $profs[$key]['selected'] = "selected";
            }
            else {
                $profs[$key]['selected'] = "";

            }
        }
        foreach($profs as $prof) {
            if ($prof['name']== $this->user->profession) {
                foreach ($prof['specializations'] as $key => $spcs) {
                    if ($prof['specializations'][$key]['name'] == $this->user->specialization) {

                        $prof['specializations'][$key]['selected'] = "selected";
                    }
                    else {
                        $prof['specializations'][$key]['selected']  = "";
                    }
                }
                $specs[] = $prof['specializations'];
            }
        }
        $this->profissions = $profs;
        $this->pspecs = $specs[0];

    }

    public function onProfSelect($prf = null) {

        if ($prf == null) {
            $selected_proff = Input::get('profession');
        }
        else {
            $selected_proff = $prf;
        }
        $file = __DIR__ . '/../data/profissions.json';
        $profs = json_decode(file_get_contents($file), true);
        foreach($profs as $prof) {
            if ($prof['name']== $selected_proff) {
                $specs[] = $prof['specializations'];
            }
        }

        return ['#spcsdiv' => $this->renderPartial('@specs.htm',[
            'specs' => $specs[0]
        ])];
    }
    public function defineProperties()
    {
        return [];
    }


    public function onProfileUpdate() {
            $rules = [
                'first_name' => 'required',
                'last_name' => 'required',
                'password' => 'required_with:password_confirmation|confirmed',
                'profession' => 'required',
                'specialization' => 'required',
                'board_number' => 'required',
              //  'subscribtion_province' => 'required',
            ];
            $req_user = [
                'first_name' => Input::get('first_name'),
                'last_name' => Input::get('last_name'),
                'password' => Input::get('password'),
                'password_confirmation' => Input::get('password_confirmation'),
                'profession' => Input::get('profession'),
                'specialization' => Input::get('specialization'),
                'board_number' => Input::get('board_number'),
            ];
            $messages = [
                'first_name.required' => trans('axen.sso::lang.messages.validation.required.name'),
                'last_name.required' => trans('axen.sso::lang.messages.validation.required.lastname'),
                'password.required' => trans('axen.sso::lang.messages.register.passwordrequired'),
                'password.confirmed' => trans('axen.sso::lang.messages.validation.confirmed.password'),
                'profession.required' => trans('axen.sso::lang.messages.validation.required.profession'),
                'specialization.required' => trans('axen.sso::lang.messages.validation.required.specialization'),
                'board_number.required' => trans('axen.sso::lang.messages.validation.required.board_number'),
               // 'subscribtion_province.required' => 'Provincia Iscrizione Ordine invalid',
            ];
            $validator = Validator::make(
                $req_user,
                $rules,
                $messages
            );
            if ($validator->fails()){
                return ['#errors'=> $this->renderPartial('Profile::errors',[
                    'errorMsgs' =>$validator->messages()->all()
                ])];
            }
            else {
                $dardy = new Sso();

                $profile = [
                    'first_name' => Input::get('first_name'),
                    'last_name' => Input::get('last_name'),
                    'profession' => Input::get('profession'),
                    'specialization' => Input::get('specialization'),
                    'board_number' => Input::get('board_number'),
                ];
                $response = $dardy->profileUpdate($profile,Input::get('sso_id'));
                if (is_array($response) && (int)$response['code']==200) {
                    $user = User::where('sso_id',Input::get('sso_id'))->first();
                    $user->first_name = Input::get('first_name');
                    $user->last_name = Input::get('last_name');
                    $user->profession = Input::get('profession');
                    $user->specialization = Input::get('specialization');
                    $user->board_number = Input::get('board_number');
                    $user->save();
                    return ['#response'=> $this->renderPartial('Profile::success',[
                        'email' => Input::get('email'),
                    ])];
                }
               else {
                return ['#errors'=> $this->renderPartial('Profile::errors',[
                    'errorMsgs' => [trans('axen.sso::lang.messages.generic')]
                    ])];
               }

            }
    }
}
