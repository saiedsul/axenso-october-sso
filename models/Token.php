<?php namespace Axen\Sso\Models;

use Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

/**
 * Model
 */
class Token extends Model
{
    use \October\Rain\Database\Traits\Validation;


    /**
     * @var string The database table used by the model.
     */
    public $table = 'axen_sso_tokens';

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];

    protected $fillable = [
        'access_key',
        'token',
        'expires_at',
    ];
    protected $casts = [
        'expires_at' => 'datetime',
    ];


}
