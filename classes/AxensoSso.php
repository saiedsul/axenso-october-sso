<?php namespace Axen\Sso\Classes;

use Carbon\Carbon;
use Axen\Sso\Models\Token;
use Axen\Sso\Models\Settings;
use Illuminate\Support\Facades\Http;
use October\Rain\Exception\ApplicationException;


class AxenSso {

    protected $grant_type;
    protected $client_secret;
    protected $client_id;
    protected $sso_base_url;
    protected $token;
    protected $client;


    public function __construct()
    {
        $settings = Settings::instance();
        if (!$settings->sso_client_id || !$settings->sso_client_secret || !$settings->sso_base_url || !$settings->sso_app_name ) {
            throw new ApplicationException('Please fill sso configurations');
        }
        $this->grant_type = 'client_credentials';
        $this->client_secret = $settings->sso_client_secret;
        $this->client_id = $settings->sso_client_id;
        $this->sso_base_url = $settings->sso_base_url;
        $this->token = $this->getToken();
    }

    public function login($email,$password) {
        $response = Http::accept('application/json')
                            ->withToken($this->token->token)
                            ->withHeaders(['origin' => config('app.url')])
                            ->post($this->sso_base_url.'/api/user/login',[
                                'email' => $email,
                                'password' => $password,
                                'client_id' => $this->client_id
                            ]);
        return $response;
    }

    public function consent($user_id) {
        $payload = [
            'client_id' => $this->client_id,
            'user_id' => $user_id
        ];
        $response = Http::accept('application/json')
                        ->withToken($this->token->token)
                        ->withHeaders(['origin' => config('app.url')])
                        ->post($this->sso_base_url.'/api/user/consent',$payload);
        return $response;
    }

    public function verifyEmail($activation_code){
        $payload = array(
            'activation_code' => $activation_code,
          );
          $response = Http::accept('application/json')
                            ->withHeaders(['origin' => config('app.url')])
                            ->withToken($this->token->token)
                            ->post($this->sso_base_url.'/api/user/activate', $payload);
          return $response;
     }

    public function resetPasswordRequest($email) {
        $payload = [
            'email' => $email,
            'client_id' => $this->client_id,
        ];
        $response = Http::accept('application/json')
                        ->withHeaders(['origin' => config('app.url')])
                        ->withToken($this->token->token)
                        ->post($this->sso_base_url.'/api/user/password/reset-request',$payload);
        return $response;
    }

    public function resetPassword($reset_token,$password,$password_confirmation) {
        $payload = [
            'reset_token' => $reset_token,
            'password' => $password,
            'password_confirmation' => $password_confirmation
        ];
        $response = Http::accept('application/json')
                        ->withHeaders(['origin' => config('app.url')])
                        ->withToken($this->token->token)
                        ->post($this->sso_base_url.'/api/user/password/reset',$payload);
        return $response;
    }

    public function register($email,$password,$password_confirmation,$profile) {
        $payload = array(
          'email' => $email,
          'password' => $password,
          'password_confirmation' => $password_confirmation,
          'profile' => $profile,
          'account_id' =>  $this->client_id,
          'client_id' =>  $this->client_id,
          'client_secret' =>  $this->client_secret,
        );
        $response = Http::accept('application/json')
                            ->withToken($this->token->token)
                            ->withHeaders(['origin' => config('app.url')])
                            ->post($this->sso_base_url.'/api/user/signup', $payload);
        return $response;
    }
    public function getProfessions() {
        $response = Http::accept('application/json')
                            ->withHeaders(['origin' => config('app.url')])
                            ->get($this->sso_base_url.'/api/data/'.$this->client_id.'/professions');
        return $response;
    }

    public function getCountries() {
        $response = Http::accept('application/json')
                            ->withHeaders(['origin' => config('app.url')])
                            ->get($this->sso_base_url.'/api/data/'.$this->client_id.'/countries');
        return $response;
    }

    public function getCities($province_short) {
        $response = Http::accept('application/json')
          ->withHeaders(['origin' => config('app.url')])
          ->get($this->sso_base_url.'/api/data/'.$this->client_id.'/cities?province='.$province_short);
        return $response;
    }

    public function getGlobalConfig() {
        $response = Http::accept('application/json')
          ->withHeaders(['origin' => config('app.url')])
          ->get($this->sso_base_url.'/api/data/configuration');
        return $response;
    }

    public function getToken() {
        $now = Carbon::now()->addHours(1);
        $existingToken = Token::where('expires_at' ,'>' ,$now )->first();
        if ($existingToken != null ) {
            return $existingToken;
        }
        $sessionToken = Http::post($this->sso_base_url.'/oauth/token', [
            'grant_type' => 'client_credentials',
            'client_id' => $this->client_id,
            'client_secret' => $this->client_secret,
        ]);

        if ($sessionToken->status() == 200) {
            $token = Token::create([
                        'access_key' => $this->client_id,
                        'token' => $sessionToken->object()->access_token ,
                        'expires_at' =>  Carbon::now()->addHours(23)
            ]);
            return $token;
        }
        else {
            return null;
        }
        return null;

    }
}
