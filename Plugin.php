<?php namespace Axenso\Sso;

use Axenso\Sso\Classes\AuthMiddleware;
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
            'name' => 'Axenso SSO V2',
            'description' => 'Provides SSO functionality for users.',
            'author' => 'Axenso S.R.L',
            'icon' => 'icon-leaf'
        ];
    }
    public function registerComponents()
    {
        return [
            'Axenso\Sso\Components\Login' => 'Login',
            'Axenso\Sso\Components\Register' => 'Register',
            'Axenso\Sso\Components\Activate' => 'Activate',
            'Axenso\Sso\Components\Protect' => 'Protect',
            'Axenso\Sso\Components\Consent' => 'Consent',
            'Axenso\Sso\Components\Logout' => 'Logout',
            'Axenso\Sso\Components\Resetpassword' => 'Resetpassword',
            'Axenso\Sso\Components\Changepassword' => 'Changepassword',
            'Axenso\Sso\Components\Registerstatus' => 'Registerstatus',
            'Axenso\Sso\Components\Profile' => 'Profile',

            // Pin blog item
            'Axenso\Sso\Components\Addtocontentlist' => 'Addtocontentlist',
            'Axenso\Sso\Components\Prefrencelist' => 'Prefrencelist',
            'Axenso\Sso\Components\Preferedposts' => 'Preferedposts',


        ];
    }

    public function registerSettings()
    {
        return [
            'settings' => [
                'label'       => 'Axenso SSO',
                'icon'        => 'icon-leaf',
                'description' => 'Configure Axenso SSO.',
                'class'       => 'Axenso\Sso\Models\Settings',
                'order'       => 600,
               // 'permissions' => ['axenso.sendinblue.configure']
            ]
        ];
    }



}
