<?php namespace Axen\Sso\Components;

use PDO;
use Carbon\Carbon;
use Axen\Sso\Classes\Sso;
use Axen\Sso\Models\User;
use Axen\Sso\Classes\AxenSso;
use Axen\Sso\Classes\Helpers;
use Axen\Sso\Models\Settings;
use Cms\Classes\ComponentBase;
use Axen\Sso\Models\Log as Axenlog;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use October\Rain\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use October\Rain\Exception\AjaxException;

class Register extends ComponentBase
{

    public $profissions;
    public $provinces;
    public $countries;
    public $xgateConfig;
    public $sitePrivacyPolicyText;
    public $sitePrivacyPolicyURL;
    public $logo;
    public $formtype;

    public function componentDetails()
    {
        return [
            'name' => 'SSO Register Form',
            'description' => 'Render registeration form'
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
      $helper = new Helpers;
      $email = Input::get('email');
      $this->page['preset_email'] = $email;
      $this->profissions = $helper->getProfissions();
      $this->provinces = $helper->getProvinceValues();
      $this->xgateConfig = $helper->getXgateConfig();
      $settings = Settings::instance();
      $this->sitePrivacyPolicyText = $settings->privacy_text;
      $this->sitePrivacyPolicyURL = $settings->privacy_policy_url;
      $this->logo = $settings->logo;
    }
    public function init(){
        if ($this->property('formtype') == null) {
            $this->formtype = 'full';
        }
        else {
            $this->formtype = $this->property('formtype');

        }
    }
    public function onSpecSelect() {
        $helper = new Helpers;
        $spec_1 = Input::get('specialization');
        $subspecs = [];
        $profs = $helper->getProfissions();
        foreach($profs as $prof) {
            foreach($prof['specializations'] as $specialization) {
                if ($specialization->name ==  $spec_1) {
                    foreach ($specialization->sub_specializations as $sub) {
                        $subspecs[] = (array)$sub;
                    }
                }
            }
        }
      return [
        '#sub_spcsdiv' => $this->renderPartial('@sub_specs.htm',['sub_spcs' => $subspecs]),
        ];
    }
    public function onRegister() {
        $settings = Settings::instance();

        $req_user = [
                    'profile' => [
                        'title' => input::get('title'),
                        'first_name' => input::get('first_name'),
                        'last_name' => input::get('last_name'),
                        'profession' => Input::get('profession'),
                        'territory' => Input::get('territory'), //
                        'specialization' => Input::get('specialization'),
                        'sub_specialization' => Input::get('sub_specialization'), // required
                        'specialization_2' => Input::get('specialization_2'), // not required
                        'specialization_3' => Input::get('specialization_3'), // not required
                        'employment' => Input::get('employment'),
                        'province_enployment' => Input::get('province_enployment'),
                        'regione_enployment' => Input::get('regione_enployment'), // required
                        'board_member' => Input::get('board_member'),
                        'board_number' => Input::get('board_number'),
                        'province_board' => Input::get('province_board'),
                        'birth_in_italy' => Input::get('birth_in_italy'),
                        'place_of_birth' => Input::get('place_of_birth'), // PLACE OF BIRTH / default IT if not exist
                        'birth_province' => Input::get('birth_province'), //
                        'birth_city' => Input::get('birth_city'), //
                        'date_of_birth' => Input::get('date_of_birth'),
                        'fiscal_code' => Input::get('fiscal_code'),
                        'custom_fiscal' => Input::get('custom_fiscal'),
                        'address_type' => Input::get('address_type'), //
                        'address_line' => Input::get('address_line'), //
                        'address_number' => Input::get('address_number'), //
                        'zip_code' => Input::get('zip_code'), //
                        'address_province' => Input::get('address_province'), //
                        'address_city' => Input::get('address_city'), //
                        'address_regione' => Input::get('address_regione'), //
                        'phone_number' => Input::get('phone_number'),
                        'gender' => Input::get('gender'), //
                        'privacy_consent' => (Input::get('privacy_consent') == null) ? 0 : 1,
                        'sso_privacy_consent' => (Input::get('privacy_consent') == null) ? 0 : 1,
                        'data_transfer_consent' => (Input::get('data_transfer_consent') == null) ? 0 : 1,
                        'data_marketing_consent' => (Input::get('data_marketing_consent') == null) ? 0 : 1,
                ],
                    'email' => Input::get('email'),
                    'email_confirmation' => Input::get('email_confirmation'),
                    'password' => Input::get('password'),
                    'password_confirmation' => Input::get('password_confirmation'),
            ];
            $sso = new AxenSso();
            $response = $sso->register($req_user['email'],$req_user['password'],$req_user['password_confirmation'],$req_user['profile']);
            $responseStatusCode = $response->getStatusCode();
            $responseObject = $response->object();
            if ($responseStatusCode == 201) {
                $dbUser = User::create([
                    'sso_id' => $responseObject->id,
                    'first_name' => Input::get('first_name'),
                    'last_name' => Input::get('last_name'),
                    'email' => Input::get('email'),
                //    'privacy_consent' => 1,
                    'password' => Hash::make(Input::get('password')),
                    'profile' => $responseObject->profile,
                    'enabled' => $responseObject->active,
                ]);
                @Axenlog::create([
                    'email' => $dbUser->email,
                    'action_type' => Axenlog::REG,
                    'action_time' => Carbon::now()->format('Y-m-d H:i:s'),
                    'user_name' => $dbUser->profile['first_name'],
                    'user_lastname' => $dbUser->profile['last_name'],
                    'sso_id' =>  $dbUser->sso_id,
                ]);
                return ['#main'=> $this->renderPartial('Register::success',[
                        'first_name' => Input::get('first_name'),
                        'last_name' => Input::get('last_name'),
                        'logo' => $settings->logo,
                    ])];
               // return response()->json($dbUser);
            }
            else if ($responseStatusCode == 500) {
                return ['#errors'=> $this->renderPartial('Register::errors',[
                    'errorMsgs' => [[trans('axen.sso::lang.messages.generic')]]
                    ])];
            }
            else {
                return ['#errors'=> $this->renderPartial('Register::errors',[
                    'errorMsgs' => (array) $responseObject->error
                ])];
            }
    }
    public function onValidateFiscal() {
        $req_user = [
            'first_name' => input::get('first_name'),
            'last_name' => input::get('last_name'),
            'birth_in_italy' => Input::get('birth_in_italy'),
            'birth_city' => Input::get('birth_city'),
            'date_of_birth' => Input::get('date_of_birth'),
            'fiscal_code' => Input::get('fiscal_code'),
            'gender' => Input::get('gender'),
            'place_of_birth' => Input::get('place_of_birth'),
            'custom_fiscal' => Input::get('custom_fiscal'),
        ];
        if ($req_user['custom_fiscal'] == 0) {
            $rules = [];
        if (Input::get('birth_in_italy') == 'EE') {
            $rules['fiscal_code'] = 'codice_fiscale:first_name=first_name,last_name=last_name,birthdate=date_of_birth,place=place_of_birth,gender=gender';
        }
        else {
            $rules['fiscal_code'] = 'codice_fiscale:first_name=first_name,last_name=last_name,birthdate=date_of_birth,place=birth_city,gender=gender';
        }
        $messages = [
            'fiscal_code' =>'Il codice fiscale  non corrisponde ai dati inseriti',
        ];
        $validator = Validator::make($req_user,$rules,$messages);
        if ($validator->fails()) {
            throw new AjaxException(['#fiscal_errors' => $this->renderPartial('@fiscal_errors.htm',[
                'errorMsgs' => $validator->customMessages,
                'currentValue' => Input::get('fiscal_code')
            ])]);
        }
        else {
            return true;
            }
        }
         else return true;
    }
    public function onValidateEmail() {
        $req_user = [
            'email' => input::get('email'),
        ];
        $rules = [
            'email' => 'required|email'
        ];
        $messages = [
            'email' =>'Inserire un indirizzo email valido',
        ];
        $validator = Validator::make($req_user,$rules,$messages);
        if ($validator->fails()) {
            throw new AjaxException(['#email_errors' => $this->renderPartial('@email_errors.htm',[
                'errorMsgs' => $validator->customMessages,
                'currentValue' => Input::get('email')
            ])]);
        }
        $sso = new AxenSso();
        $response = $sso->lookUpEmail($req_user['email']);
        $responseStatusCode = $response->getStatusCode();
        if ($responseStatusCode != 200) {
            throw new AjaxException(['#email_errors' => $this->renderPartial('@email_errors.htm',[
                'errorMsgs' => ['Tutti i siti di Axenso sono gestiti da un sistema di registrazione centralizzata (SSO Axenso) che permette il login con le medesime credenziali. La mail indicata risulta essere già esistente su SSO Axenso. <a href="/login">Accedi</a> al sito come utente registrato inserendo le tue credenziali. Se non ricordi la password utilizza la funzionalità <a href="/recupera-password">recupera password</a>'],
                'currentValue' => Input::get('email')
            ])]);
        }
    }
    public function onCitySelect() {
        $helper = new Helpers;
        $city = Input::get('province_enployment');
        $region = $helper->get_region_by_city($city);

        return [
            '#regionDiv' => $this->renderPartial('@region.htm',['region' => $region]),
        ];
    }
    public function onProvinceSelect() {
        $helper = new Helpers;
        $province = Input::get('birth_province');
        $cities = $helper->getCities($province);
        return [
            '#birth_city' => $this->renderPartial('@birth_cities.htm',['cities' => $cities]),
        ];
    }
    public function onAddressProvinceSelect() {
        $helper = new Helpers;
        $province = Input::get('address_province');
        $region = $helper->get_region_by_city($province);

        $cities = $helper->getCities($province);
        return [
            '#address_city' => $this->renderPartial('@address_cities.htm',['cities' => $cities]),
            '#AddressregionDiv' => $this->renderPartial('@address_region.htm',['region' => $region]),
        ];
    }
    public function onCountryCheck() {
        $helper = new Helpers;
        $opts =  Input::get('birth_in_italy');
        if ($opts == 'EE') {
            return [
                '#countryDiv' => $this->renderPartial('@countries.htm',['countries' => $helper->getCountries()]),
                '#birth_prov' =>  $this->renderPartial('@empty.htm',['provinces' => []]),
                '#birth_city' =>  $this->renderPartial('@empty.htm',['cities' => []])
            ];
        }
        else {
            return [
                '#countryDiv' => $this->renderPartial('@countries.htm',['countries' => []]),
                '#birth_prov' =>  $this->renderPartial('@birth_povinces.htm',['provinces' => $helper->getProvinceValues()]),
                '#birth_city' =>  $this->renderPartial('@birth_cities.htm',['cities' => []])
            ];
        }
    }
    public function onProfSelect() {
        $helper = new Helpers;
        $specs = [];
        $profs = $helper->getProfissions();
        foreach($profs as $prof) {
            if ($prof['name']== Input::get('profession')) {
                foreach ($prof['specializations'] as $sp) {
                    $specs[] = (array)$sp;
                }
            }
        }
        return [
            '#spcsdiv_1' => $this->renderPartial('@specsOne.htm',['specsOne' => $specs]),
            '#spcsdiv_2' => $this->renderPartial('@specsTwo.htm',['specsTwo' => (sizeof($specs) > 1) ? $specs : []]),
            '#spcsdiv_3' => $this->renderPartial('@specsThree.htm',['specsThree' => (sizeof($specs) > 2) ? $specs : []]),

        ];
    }
    public function defineProperties()
    {
        return [
            'formtype' => [
                'title' => 'Form Type',
                'type' => 'dropdown',
               // 'default' => 'imperial',
                'placeholder' => 'Select Form Type',
                'required' => true,
                'options' => [
                    'full' => 'FULL FORM',
                    'light' => 'LIGHT FORM'
                ]
            ]
        ];
    }


}
