<?php namespace Axen\Sso\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateAxenSsoUsers extends Migration
{
    public function up()
    {
        Schema::create('axen_sso_users', function($table)
        {
            $table->engine = 'InnoDB';
            $table->bigIncrements('id')->unsigned();
            $table->bigInteger('sso_id');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email');
            $table->string('password');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->smallInteger('boarded')->nullable()->default(0);
            $table->smallInteger('enabled')->default(0);
        });
    }

    public function down()
    {
        Schema::dropIfExists('axen_sso_users');
    }
}
