<?php namespace Axen\Sso\Components;

use Axen\Sso\Models\User;
use RainLab\Blog\Models\Post;
use Cms\Classes\ComponentBase;
use Axen\Sso\Models\UserList;
use Illuminate\Support\Facades\Log;
use Axen\Sso\Models\PreferenceList;
use Illuminate\Support\Facades\Cookie;
use October\Rain\Support\Facades\Input;
use GinoPane\BlogTaxonomy\Models\Series;
use Illuminate\Support\Facades\Redirect;
use AnandPatel\SeoExtension\Models\BlogPost;

class Prefrencelist extends ComponentBase
{
    public $user;
    public $series;
    public $prefrences;
    public function componentDetails()
    {
        return [
            'name'        => 'User Prefrences',
            'description' => 'Show User Prefrence list form'
        ];
    }
    public function onRun()
    {
        $this->addJs('/plugins/axen/sso/assets/js/sso.js',[
            'type' => "text/javascript",
        ]);
        $this->addCss('/plugins/axen/sso/assets/css/sso.css');
        $cookie = Cookie::get('user_id');
        $this->user =  User::where('sso_id',$cookie)->first();

    }
    function onRender() {
        $pref = [];
        if ($this->user == null) {return false;}
        $prefs = PreferenceList::where( 'user_id' , $this->user->id)->first();
        if (!empty($prefs)) {
            $pref = $prefs->prefrences;
            $pref = explode(',',$pref);
        }
        $series = Series::where('status','active')->get()->map(function($item) use ($pref){
            if (in_array($item->id,$pref)) {
                $item->selected = 1;
            }
            return $item;
        });
        $this->series = $series;
    }

    public function onUpdatePref() {
        if (Input::get('userprefrences')) {
            $prefernces = Input::get('userprefrences');
        }
        else {
            $prefernces = [];
        }
        $cookie = Cookie::get('user_id');
        $user =  User::where('sso_id',$cookie)->first();
        $groups = [];
        foreach ($prefernces as $key=>$value) {
            $groups[] = $value;
        }
        $groups = implode(',',$groups);
        $list = PreferenceList::where( 'user_id' , $user->id)->first();
        if ($list) {
            $list->prefrences = $groups;
            $list->save();
        }
        else {
            PreferenceList::create([
                'user_id' => $user->id,
                'prefrences' => $groups,
            ]);
        }
        if ($user->boarded == 0) {
            $user->boarded = 1;
            $user->save();
        }
        if ($user->sso_privacy_consent == 0) {
            $user->sso_privacy_consent = 1;
            $user->save();
        }

    }

    public function defineProperties()
    {
        return [
            'itemid' => [
                'title' => 'item id',
                'description' => '',
                'type' => 'string',
                'required' => false,
            ],
            'itemtype' => [
                'title' => 'item type',
                'description' => '',
                'type' => 'string',
                'required' => false,
            ],
        ];
    }
}
