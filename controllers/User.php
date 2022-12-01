<?php namespace Axen\Sso\Controllers;

use Backend\Classes\Controller;
use BackendMenu;

class User extends Controller
{
    public $implement = [
        'Backend\Behaviors\ListController',
        'Backend\Behaviors\FormController'  
        ,\Backend\Behaviors\ImportExportController::class  
    ];
    
    public $listConfig = 'config_list.yaml';
    public $formConfig = 'config_form.yaml';
    public $importExportConfig = 'config_import_export.yaml';
    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('axen.october-sso', 'main-menu-item');
    }
}
