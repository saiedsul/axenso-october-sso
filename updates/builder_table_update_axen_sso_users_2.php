<?php namespace Axen\Sso\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateAxenSsoUsers2 extends Migration
{
    public function up()
    {
        Schema::table('axen_sso_users', function($table)
        {
            $table->smallInteger('privacy_consent')->default(0);
            $table->smallInteger('profile_updated')->default(0);
        });
    }
    
    public function down()
    {
        Schema::table('axen_sso_users', function($table)
        {
            $table->dropColumn('privacy_consent');
            $table->dropColumn('profile_updated');
        });
    }
}
