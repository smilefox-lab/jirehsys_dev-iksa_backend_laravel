<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RealEstateAddColumnsFilesInRePropertiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('re_properties', function (Blueprint $table) {
            $table->text('files_technical')->nullable();
            $table->text('files_legal')->nullable();
            $table->text('files_plane')->nullable();
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
            $table->dropColumn('files_plane');
            $table->dropColumn('files_legal');
            $table->dropColumn('files_technical');
        });
    }
}
