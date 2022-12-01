<?php namespace Axen\Sso\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateAxenSsoTokens extends Migration
{
    public function up()
    {
        Schema::create('axen_sso_tokens', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->integer('app_id')->default(0);
            $table->string('access_key', 255);
            $table->text('token')->nullable(false)->unsigned(false)->default(null);
            $table->dateTime('expires_at');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('axen_sso_tokens');
    }
}
