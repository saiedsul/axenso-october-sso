<?php namespace Axenso\Sso\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateAxensoSsoPrefrenceList extends Migration
{
    public function up()
    {
        Schema::create('axenso_sso_prefrence_list', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->integer('user_id');
            $table->string('prefrences');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('axenso_sso_prefrence_list');
    }
}
