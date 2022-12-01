<?php namespace Axenso\Sso\Models;

use Model;
use Axenso\Sso\Models\User;

/**
 * Model
 */
class Log extends Model
{
    use \October\Rain\Database\Traits\Validation;
    
    protected $fillable = [
        'user_id',
        'action_type',
        'action_time',
        'user_name',
        'user_lastname',
        'email',
        'sso_id'
    ];
    const LOGIN = 'LOGIN';
    const REG = 'REGISTER';
    const RPAS = 'RESETPASSWORD';
    /**
     * @var string The database table used by the model.
     */
    public $table = 'axenso_sso_logs';

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];
    
    public $belongsTo = [
        'user' => [User::class, 'key' => 'sso_id','otherKey' => 'sso_id']
    ];

}
