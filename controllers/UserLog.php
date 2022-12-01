<?php namespace Axenso\Sso\Controllers;

use Backend\Classes\Controller;
use BackendMenu;

class UserLog extends Controller
{
    public $implement = [
        'Backend\Behaviors\ListController'
        ,\Backend\Behaviors\ImportExportController::class  
    ];
    
    public $listConfig = 'config_list.yaml';
    public $importExportConfig = 'config_import_export.yaml';

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('axenso.october-sso', 'main-menu-item', 'side-menu-item');
    }
}
