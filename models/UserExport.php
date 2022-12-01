<?php namespace Axen\Sso\Models;


use Backend\Models\ExportModel;

class UserExport extends ExportModel
{
    public $table = 'axen_sso_users';

    public function exportData($columns, $sessionKey = null)
    {
        return self::make()->get()->toArray();
    }
}