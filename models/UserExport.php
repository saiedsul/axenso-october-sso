<?php namespace Axenso\Sso\Models;


use Backend\Models\ExportModel;

class UserExport extends ExportModel
{
    public $table = 'axenso_sso_users';

    public function exportData($columns, $sessionKey = null)
    {
        return self::make()->get()->toArray();
    }
}