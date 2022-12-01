<?php namespace Axenso\Sso\Components;

use AnandPatel\SeoExtension\Models\BlogPost;
use Axenso\Sso\Models\User;
use Axenso\Sso\Models\UserList;
use Cms\Classes\ComponentBase;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cookie;
use October\Rain\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use RainLab\Blog\Models\Post;

class Addtocontentlist extends ComponentBase
{
    public $itemid;
    public $itemtype;
    public $pinned;
    public $user;
    public $items;
    public function componentDetails()
    {
        return [
            'name'        => 'Pin Content',
            'description' => 'Show Pin to add content to list'
        ];
    }
    public function onRun()
    {
        $cookie = Cookie::get('user_id');
        $this->user =  User::where('sso_id',$cookie)->first();
        $list =[];
        if ($this->user) {
            $this->items = $this->user->items;
        }
        else {
            $this->items = [];
        }
        if ($this->user != null) {
            $blogs = $this->user->items->toArray();
            foreach($blogs as $item) {
                $list[] = $item['content_id'];
            }
        }
     
        $this->items = $list;
       
    }
    function onRender() {
        $this->itemid = $this->property('itemid');
        $this->itemtype = $this->property('itemtype');
        $list=[];
        if ($this->user != null) {
            $blogs = $this->user->items->toArray();
            foreach($blogs as $item) {
                $list[] = $item['content_id'];
            }
        // if ($this->user->whereHas('items' , function($q) {
        //     $q->where('content_id',$this->property('itemid'))->where('content_type',$this->property('itemtype'));
        // })->count() > 0) {
        //     $this->pinned = 1;
        // }
        // else {
        //     $this->pinned = 0;
        // }
        if (in_array((int)$this->property('itemid'),$this->items)) {
            $this->pinned = 1;
        }
        else {
            $this->pinned = 0;
        }
        }
     }
    public function onAddItem() {
        $cookie = Cookie::get('user_id');
        $user = User::where('sso_id',$cookie)->first();
        $list=[];
        if ($user) {
            $blogs = $user->items;
            if (!empty($blogs)) {
                foreach($blogs->toArray() as $item) {
                    $list[] = $item['content_id'];
                }
            }
            
            if (!in_array((int)Input::get('itemid'),$list))  {
                $user->items()->create([
                    'content_id' => (int)Input::get('itemid'),
                    'content_type' => Input::get('itemtype'),
                    'user_id' => $user->id,
                    'sso_id' => $user->sso_id
                ]);
            }
            else {
                UserList::where('content_id',Input::get('itemid'))->where('content_type',Input::get('itemtype'))->where('user_id',$user->id)->delete();
            }
           
        }
        $posts = UserList::where('user_id',$user->id)->get()->map(function($item){
            if ($item->content_type == 'blogpost') {
                $post =  Post::find($item->content_id);
                return $post;
            }
        });
        return ['#my-content' => $this->renderPartial('@items.htm',[
            'items' => $posts
        ])];

    }
    public function onRemoveItem() {
        $cookie = Cookie::get('user_id');
        $user = User::where('sso_id',$cookie)->first();
        
        UserList::where('content_id',Input::get('itemid'))->where('user_id',$user->id)->delete();
        $posts = UserList::where('user_id',$user->id)->get()->map(function($item){
            if ($item->content_type == 'blogpost') {
                $post =  Post::find($item->content_id);
                return $post;
            }
        });
        return ['#my-content' => $this->renderPartial('@items.htm',[
            'items' => $posts
        ])];
    }
    public function defineProperties()
    {
        return [
            'itemid' => [
                'title' => 'item id',
                'description' => '',
                'type' => 'string',
                'required' => false,
            ],
            'itemtype' => [
                'title' => 'item type',
                'description' => '',
                'type' => 'string',
                'required' => false,
            ],
        ];
    }
}
