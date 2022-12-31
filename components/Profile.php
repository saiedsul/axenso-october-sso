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
use Illuminate\Support\Facades\Cookie;
class Profile extends ComponentBase
{
    public $user;
    public $profissions;
    public $provinces;
    public $countries;
    public $xgateConfig;
    public $sitePrivacyPolicyText;
    public $sitePrivacyPolicyURL;
    public $logo;
    public $formtype;
    public $profile;
    public $isForeign;
    public $initial_sepcs_1;
    public $specsTwo;
    public $specsThree;
    public $sub_spcs;
    public $cities;
    public $address_cities;
    public function componentDetails()
    {
        return [
            'name'        => 'SSO Profile Form',
            'description' => 'Render profile form'
        ];
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
        if ($req_user['custom_fiscal'] == null) {
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

    public function onProfileUpdate() {
        $settings = Settings::instance();
        $cookie = Cookie::get('user_id');
        $this->user = $user = User::where('sso_id',$cookie)->first();
        if ($this->property('formtype') == 'full') {
            $req_user_initial = [
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
            if ($req_user_initial['custom_fiscal'] == null) {
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
            $validator = Validator::make($req_user_initial,$rules,$messages);
                if ($validator->fails()) {
                    return ['#fiscal_errors'=> $this->renderPartial('Profile::fiscal_errors',[
                        'errorMsgs' => $validator->customMessages,
                        'currentValue' => Input::get('fiscal_code')
                    ])];

                }
            }
        }

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
                        'address_type' => Input::get('address_type'), //
                        'address_line' => Input::get('address_line'), //
                        'address_number' => Input::get('address_number'), //
                        'zip_code' => Input::get('zip_code'), //
                        'address_province' => Input::get('address_province'), //
                        'address_city' => Input::get('address_city'), //
                        'address_regione' => Input::get('address_regione'), //
                        'phone_number' => Input::get('phone_number'),
                        'gender' => Input::get('gender'), //
                        'privacy_consent' => 1,
                        'sso_privacy_consent' =>  1,
                        'data_transfer_consent' => (Input::get('data_transfer_consent') == null) ? 0 : 1,
                        'data_marketing_consent' => (Input::get('data_marketing_consent') == null) ? 0 : 1,
                ],
            ];
            $sso = new AxenSso();
            $response = $sso->updateProfile($user->sso_id,$req_user['profile']);
            $responseStatusCode = $response->getStatusCode();
            $responseObject = $response->object();
            if ($responseStatusCode == 200) {
                $user->profile = $req_user['profile'];
                $user->profile_updated = 1;
                $user->privacy_consent = 1;
                $user->save();
                return ['#main'=> $this->renderPartial('Profile::success',[
                        'first_name' => Input::get('first_name'),
                        'last_name' => Input::get('last_name'),
                        'logo' => $settings->logo,
                    ])];
               // return response()->json($dbUser);
            }
            else if ($responseStatusCode == 500) {
                return ['#errors'=> $this->renderPartial('Profile::errors',[
                    'errorMsgs' => [[trans('axen.sso::lang.messages.generic')]]
                    ])];
            }
            else {
                return ['#errors'=> $this->renderPartial('Profile::errors',[
                    'errorMsgs' => (array) $responseObject->error
                ])];
            }
    }


    public function onRun(){
        $this->addJs('/plugins/axen/sso/assets/js/sso.js',[
            'type' => "text/javascript",
        ]);
        $this->addJs('https://kit.fontawesome.com/6d76af8390.js',[
            'type' => "text/javascript",
        ]);
        $this->addCss('/plugins/axen/sso/assets/css/sso.css');
        $helper = new Helpers;
        $settings = Settings::instance();
        $this->profissions = $helper->getProfissions();
        $this->provinces = $helper->getProvinceValues();
        $this->xgateConfig = $helper->getXgateConfig();
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
    public function onRender() {
        $cookie = Cookie::get('user_id');
        $this->user = $user = User::where('sso_id',$cookie)->first();
        $sso = new AxenSso;
        $helper = new Helpers;
        $settings = Settings::instance();
        $response = $sso->getProfile($user->sso_id);
        $responseStatusCode = $response->getStatusCode();
        $responseObject = $response->object();
        if ($responseStatusCode == 200) {

            $this->profile = $profile = $responseObject;
            //exit($profile->profession);
            $this->specsTwo = [];
            $this->specsThree = [];
            $this->initial_sepcs_1 = $initial_sepcs_1 = $this->getProfSpec($profile->profession);
            $this->sub_spcs = $this->getProfileSubSpecs($profile->specialization);
            $this->cities =  $this->getCityByProvince(@$profile->birth_province);
            $this->address_cities =  $this->getCityByProvince(@$profile->address_province);

            if ($initial_sepcs_1 > 1 ){
                $this->specsTwo = $initial_sepcs_1;
            }
            if ($initial_sepcs_1 > 2) {
                $this->specsThree = $initial_sepcs_1;
            }
        }
        else {
            exit('ss');
        }

       $this->countries =  $countries = $helper->getCountries();
        $this->isForeign = 0;
        foreach($countries as $country) {
            if ($country['name'] == $profile->place_of_birth) {
                $this->isForeign = 1;
                break;
            }
        }
        if (Input::get('dubug') == 1) {
            dd($responseObject);
        }


    }
    public function getProfileSubSpecs($item) {
        $helper = new Helpers;
        $spec_1 = $item;
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
        return $subspecs;
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
    public function getCityByProvince($usrprovince = null) {
        $helper = new Helpers;
        $province = $usrprovince;
        $cities = $helper->getCities($province);
        return $cities;
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
    public function getProfSpec($proff) {
        $helper = new Helpers;
        $specs = [];
        $profs = $helper->getProfissions();
        foreach($profs as $prof) {

            if ($prof['name'] === $proff) {
                foreach ($prof['specializations'] as $sp) {
                    $specs[] = (array)$sp;
                }
            }
        }
        return $specs;
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
