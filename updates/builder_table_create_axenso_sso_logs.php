<?php namespace Axen\Sso\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateAxenSsoLogs extends Migration
{
    public function up()
    {
        Schema::create('axen_sso_logs', function($table)
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
        Schema::dropIfExists('axen_sso_logs');
    }
}
