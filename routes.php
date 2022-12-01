
<?php
/*        $url = config('sso.sso_base_url').'/user/activate/';
        $data = $dardy->activateUser($request->code,$url);
        */

use Axenso\Sso\Classes\Sso;
use Axenso\Sso\Classes\AxensoSso;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Redirect;

Route::group(['as'=>'sso'], function() {


Route::get('/ssotest',function() {

    $sso = new AxensoSso();

    $response = $sso->login('saied.suliman@axenso.com','password');
    dd($response->object());


});

});


?>
