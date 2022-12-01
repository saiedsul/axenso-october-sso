<?php namespace Axenso\Sso\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateAxensoSsoLogs extends Migration
{
    public function up()
    {
        Schema::create('axenso_sso_logs', function($table)
        {
            $table->engine = 'InnoDB';
            $table->bigIncrements('id')->unsigned();
            $table->integer('sso_id')->nullable();
            $table->string('action_type');
            $table->text('user_name');
            $table->text('user_lastname');
            $table->string('email');
            $table->dateTime('action_time');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('axenso_sso_logs');
    }
}
