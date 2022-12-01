<?php namespace Axen\Sso;

use Axen\Sso\Classes\AuthMiddleware;
use System\Classes\PluginBase;

class Plugin extends PluginBase
{
    public function boot()
    {
    // Add a new middleware to beginning of the stack.
    // $this->app['Illuminate\Contracts\Http\Kernel']
    //      ->pushMiddleware(AuthMiddleware::class);
    // $page = new \Cms\Classes\Page([
    //     'fileName' => 'test-page',
    //     'title' => 'My Page Title',
    //     'url' => '/test-page',
    //     'layout' => 'default',
    // ]);

  //  $page->attributes['myComponent'] = [];

    //$page->save();

    }
    public function pluginDetails()
    {
        return [
            'name' => 'Axen SSO V2',
            'description' => 'Provides SSO functionality for users.',
            'author' => 'Axen S.R.L',
            'icon' => 'icon-leaf'
        ];
    }
    public function registerComponents()
    {
        return [
            'Axen\Sso\Components\Login' => 'Login',
            'Axen\Sso\Components\Register' => 'Register',
            'Axen\Sso\Components\Activate' => 'Activate',
            'Axen\Sso\Components\Protect' => 'Protect',
            'Axen\Sso\Components\Consent' => 'Consent',
            'Axen\Sso\Components\Logout' => 'Logout',
            'Axen\Sso\Components\Resetpassword' => 'Resetpassword',
            'Axen\Sso\Components\Changepassword' => 'Changepassword',
            'Axen\Sso\Components\Registerstatus' => 'Registerstatus',
            'Axen\Sso\Components\Profile' => 'Profile',

            // Pin blog item
            'Axen\Sso\Components\Addtocontentlist' => 'Addtocontentlist',
            'Axen\Sso\Components\Prefrencelist' => 'Prefrencelist',
            'Axen\Sso\Components\Preferedposts' => 'Preferedposts',


        ];
    }

    public function registerSettings()
    {
        return [
            'settings' => [
                'label'       => 'Axen SSO',
                'icon'        => 'icon-leaf',
                'description' => 'Configure Axen SSO.',
                'class'       => 'Axen\Sso\Models\Settings',
                'order'       => 600,
               // 'permissions' => ['axen.sendinblue.configure']
            ]
        ];
    }



}
