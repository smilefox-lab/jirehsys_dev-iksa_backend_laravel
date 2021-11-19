<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnAbbreviationToStatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('states', function (Blueprint $table) {
            $table->string('abbreviation', 2)->nullable();
        });

        Schema::table('cities', function (Blueprint $table) {
            $table->string('record_id', 40)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('states', function (Blueprint $table) {
            $table->dropColumn('abbreviation');
        });

        Schema::table('cities', function (Blueprint $table) {
            $table->dropColumn('record_id');
        });
    }
}
