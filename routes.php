
<?php
/*        $url = config('sso.sso_base_url').'/user/activate/';
        $data = $dardy->activateUser($request->code,$url);
        */

use Axen\Sso\Classes\Sso;
use Axen\Sso\Classes\AxenSso;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Redirect;

Route::group(['as'=>'sso'], function() {


Route::get('/ssotest',function() {

    $sso = new AxenSso();

    $response = $sso->login('saied.suliman@axen.com','password');
    dd($response->object());


});

});


?>
