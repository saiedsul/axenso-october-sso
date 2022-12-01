<?php namespace Axen\Sso\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateAxenSsoContentList extends Migration
{
    public function up()
    {
        Schema::create('axen_sso_content_list', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->integer('sso_id')->nullable();
            $table->integer('user_id');
            $table->integer('content_id');
            $table->string('content_type');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('axen_sso_content_list');
    }
}
