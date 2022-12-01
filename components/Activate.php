<?php namespace Axenso\Sso\Components;

use Axenso\Sso\Classes\AxensoSso;
use Axenso\Sso\Classes\Sso;
use Cms\Classes\ComponentBase;
use October\Rain\Support\Facades\Input;

class Activate extends ComponentBase
{
    public $code;
    public function componentDetails()
    {
        return [
            'name'        => 'User Email verification',
            'description' => ''
        ];
    }
    public function init() {
        $this->code = Input::get('code');
    }
    public function onRender() {
        if ($this->code == null) {
            return $this->renderPartial('@errors.htm');
        }
        $sso = new AxensoSso();
        $response = $sso->verifyEmail($this->code);
        if ($response->getStatusCode() == 201) {
            return $this->renderPartial('@success.htm');
        }
        else {
            return $this->renderPartial('@errors.htm');
        }

    }
    public function defineProperties()
    {
        return [];
    }
}
