<?php

namespace Botble\RealEstate;

use Botble\PluginManagement\Abstracts\PluginOperationAbstract;
use Illuminate\Database\Schema\Blueprint;
use Schema;

class Plugin extends PluginOperationAbstract
{
    public static function remove()
    {
        Schema::disableForeignKeyConstraints();

        Schema::dropIfExists('consults');
        Schema::dropIfExists('re_investors');
        Schema::dropIfExists('re_projects');
        Schema::dropIfExists('re_properties');
        Schema::dropIfExists('re_features');
        Schema::dropIfExists('re_property_features');
        Schema::table('re_properties', function (Blueprint $table) {
            $table->dropColumn('destiny_id');
        });

        Schema::table('re_projects', function (Blueprint $table) {
            $table->dropColumn('destiny_id');
        });
        Schema::dropIfExists('re_destinies');
        Schema::dropIfExists('currencies');
    }
}
