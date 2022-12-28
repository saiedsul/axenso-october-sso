<?php namespace Axen\Sso\Components;

use Axen\Sso\Classes\Sso;
use Axen\Sso\Classes\AxenSso;
use Axen\Sso\Models\Settings;
use Cms\Classes\ComponentBase;
use October\Rain\Support\Facades\Input;

class Activate extends ComponentBase
{
    public $code;
    public $logo;
    public function componentDetails()
    {
        return [
            'name' => 'User Email verification',
            'description' => ''
        ];
    }
    public function onRun() {
        $this->addJs('/plugins/axen/sso/assets/js/sso.js',[
            'type' => "text/javascript",
        ]);
        $this->addCss('/plugins/axen/sso/assets/css/sso.css');
        $settings = Settings::instance();
        $this->logo = $settings->logo;
      }
    public function init() {
        $this->code = Input::get('code');
    }
    public function onRender() {
        $settings = Settings::instance();
        if ($this->code == null) {
            return $this->renderPartial('@errors.htm',[
                'logo' => $settings->logo
            ]);
        }
        $sso = new AxenSso();
        $response = $sso->verifyEmail($this->code);
        if ($response->getStatusCode() == 201) {
            return $this->renderPartial('@success.htm',[
                'logo' => $settings->logo
            ]);
        }
        else {
            return $this->renderPartial('@errors.htm',[
                'logo' => $settings->logo
            ]);
        }

    }
    public function defineProperties()
    {
        return [];
    }
}
