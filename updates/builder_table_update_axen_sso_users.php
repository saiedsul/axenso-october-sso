<?php namespace Axen\Sso\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateAxenSsoUsers extends Migration
{
    public function up()
    {
        Schema::table('axen_sso_users', function($table)
        {
            $table->jsonb('profile')->nullable();
        });
    }

    public function down()
    {
        Schema::table('axen_sso_users', function($table)
        {
            $table->dropColumn('profile');
        });
    }
}
