<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeNameColumnCityIdToCommuneIdInRePropertiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('re_properties', function (Blueprint $table) {
            $table->renameColumn('city_id', 'commune_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('re_properties', function (Blueprint $table) {
            $table->renameColumn('commune_id', 'city_id');
        });
    }
}
