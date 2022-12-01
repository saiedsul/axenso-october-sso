<?php namespace Axen\Sso\Models;

use Model;
use Axen\Sso\Models\Log;
use Axen\PostRate\Models\Rating;
use Axen\Sso\Models\PreferenceList;


/**
 * Model
 */
class User extends Model
{
    use \October\Rain\Database\Traits\Validation;


    /**
     * @var string The database table used by the model.
     */
    public $table = 'axen_sso_users';

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];

    protected $fillable= [
        'sso_id',
        'first_name',
        'last_name',
        'email',
        'password',
        'profession',
        'specialization',
        'fiscal_code',
        'date_of_birth',
        'place_of_birth',
        'phone_number',
        'province_board',
        'sso_privacy_consent',
        'privacy_consent',
        'news_consent',
        'data_transfer_consent',
        'data_marketing_consent',
        'board_number',
        'board_member',
        'enabled',
        'profile'
    ];
    public static function hasConsent($user) {
        foreach($user->profile->app_consents as $consent) {
            if ($consent->privacy == true && $consent->app_name == config('sso.app_name') && $consent->app_id == config('sso.app_id')) {
                return true;
            }
        }
        return false;
    }
    protected $casts = [
        'profile' => 'json',
    ];
    public $hasMany = [
        'items' => \Axen\Sso\Models\UserList::class,
        'logs' =>   [Log::class, 'key' => 'sso_id','otherKey' => 'sso_id']

     //   'ratings' => Rating::class,
    ];
    public $hasOne = [
        'preferences' => PreferenceList::class,
    ];
}
