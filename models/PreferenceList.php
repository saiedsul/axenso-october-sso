<?php namespace Axenso\Sso\Models;

use Model;
use Axenso\Sso\Models\User;

/**
 * Model
 */
class PreferenceList extends Model
{
    use \October\Rain\Database\Traits\Validation;
    protected $fillable = [
        'id',
        'user_id',
        'prefrences',
    ];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'axenso_sso_prefrence_list';
    public $belongsTo = [
        'user' => [User::class, 'key' => 'user_id']
    ];

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];
}
