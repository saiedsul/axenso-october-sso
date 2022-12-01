<?php namespace Axen\Sso\Classes;

use Axen\Sso\Models\Token;

class Sso {
    protected $token;
    public function __construct()
    {
        $this->token = Token::DardyToken();
    }

    public function registerUser($user) {
        $payload = array( 'user' =>
        array (
          'email' => $user['email'],
          'password' => $user['password'],
          'password_confirmation' => $user['password'],
          'callback_url' => config('sso.call_back_url').'/user-activation',
          'profile' =>
          array (
            'first_name' => $user['first_name'],
            'last_name' => $user['last_name'],
            'fiscal_code' =>  'NOT-USED',
            'date_of_birth' => 'NOT-USED',
            'place_of_birth' => 'NOT-USED',
            'phone_number' => 'NOT-USED',
            'profession' => $user['profession'],
            'specialization' => $user['specialization'],
            'board_member' => 'NOT-USED',
            'board_number' => $user['board_number'],//TBU
            'province_board' => 'NOT-USED',
            'employment' => 'NOT-USED',
            'province_enployment' => 'NOT-USED',
            'privacy_consent' => true, // correct
            'sso_privacy_consent' => true, // correct
          ),
        ));
        $url = config('sso.base_url');
        $result = $this->curlExe($url.'/user/signup',$payload);
        return $result;
    }
    public function activateUser($code,$url) {
        $data = [];
        $curl = curl_init($url.$code);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "Accept: application/vnd.dardy.sso.v1+json","Authorization: Dardy ".$this->token->token));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
        $result = curl_exec($curl);
        $httpcode = (int)curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $data = [
          'code' => $httpcode,
          'data' => (array)json_decode($result),
        ];
        curl_close($curl);
        return $data;
    }
    public function loginUser($user) {
        $payload = array('user' =>
        array (
          'email' => $user['email'],
          'password' => $user['password'],
        ));
        $url = config('sso.base_url');
        $result = $this->curlExe($url.'/user/signin',$payload);
        return $result;
    }
    public function consetnUser($user) {
        $payload = array('profile'=> array(
              'privacy_consent' => true,
        ));
        $url = config('sso.base_url');
        $result = $this->curlExe($url.'/user/'.$user["id"].'/profile',$payload,'PUT');
        return $result;
    }
    public function profileUpdate($profile,$user_id) {
      $payload = array('profile'=> $profile);
      $url = config('sso.base_url');
      $result = $this->curlExe($url.'/user/'.$user_id.'/profile',$payload,'PUT');
      return $result;
    }

    public function passwordRequest($email) {
      $payload = array('user' =>
      array (
        'email' => $email,
        'callback_url' => config('sso.call_back_url').'/password-reset',
      ));
      $url = config('sso.base_url');
      $result = $this->curlExe($url.'/user/password/reset',$payload);
      return $result;
    }

    public function passwordChange($password,$code) {
      $payload = array('user'=> array(
        'password' => $password,
        'password_confirmation' => $password
      ));
      $url = config('sso.base_url');
      $result = $this->curlExe($url.'/user/password/reset/'.$code,$payload,'PUT');
      return $result;
    }

    public function getProfessions() {

    }

    private function curlExe($url,$payload = null,$method = 'POST') {
        $data = [];
        $curl = curl_init($url);
    //    curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "Accept: application/vnd.dardy.sso.v1+json","Authorization: Dardy ".$this->token->token));
        if ($payload != null) {

          if ($method == 'POST') {
            $payload = json_encode($payload);
            curl_setopt($curl, CURLOPT_POSTFIELDS,$payload);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
          }
          else if ($method == 'PUT') {
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PUT');
            curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($payload));
          }

          $result = curl_exec($curl);
          $httpcode = (int)curl_getinfo($curl, CURLINFO_HTTP_CODE);
          $data = [
              'code' => $httpcode,
              'data' => (array)json_decode($result),
          ];
          curl_close($curl);
        }
        return $data;
    }

}
