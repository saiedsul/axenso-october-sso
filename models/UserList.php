<?php namespace Axenso\Sso\Models;

use Model;
use RainLab\Blog\Models\Post;
use Illuminate\Support\Facades\Cookie;

/**
 * Model
 */
class UserList extends Model
{
    use \October\Rain\Database\Traits\Validation;
    
    protected $fillable = [
        'id',
        'content_id',
        'user_id',
        'sso_id',
        'content_type'
    ];
    /**
     * @var string The database table used by the model.
     */
    public $table = 'axenso_sso_content_list';

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];
    public $belongsTo = [
        'post' => [Post::class, 'key' => 'content_id']
    ];

    public function scopeUser($query,$value ='') {
        $cookie = Cookie::get('user_id');
        $user = User::where('sso_id',$cookie)->first();
        if ($user == null) return;
        $query->where('user_id',$user->id)->with('post')->get();
    }
}
