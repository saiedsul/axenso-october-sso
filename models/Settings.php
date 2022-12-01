<?php namespace Axenso\Sso\Models;

use October\Rain\Database\Model;

/**
 * SSO settings model
 *
 * @package system
 * @author XX <october@axenso.com>
 *
 */
class Settings extends Model
{
    use \October\Rain\Database\Traits\Validation;

    public $implement = ['System.Behaviors.SettingsModel'];

    public $settingsCode = 'axenso_sso_settings';

    public $settingsFields = 'fields.yaml';

    /**
     * Validation rules
     */
    public $rules = [
        'sso_client_id' => 'required',
        'sso_client_secret' => 'required',
        'sso_base_url' => 'required',
        'sso_app_name' => 'required'
    ];
}
