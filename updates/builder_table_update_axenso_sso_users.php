<?php namespace Axenso\Sso\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateAxensoSsoUsers extends Migration
{
    public function up()
    {
        Schema::table('axenso_sso_users', function($table)
        {
            $table->jsonb('profile')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('axenso_sso_users', function($table)
        {
            $table->dropColumn('profile');
        });
    }
}
