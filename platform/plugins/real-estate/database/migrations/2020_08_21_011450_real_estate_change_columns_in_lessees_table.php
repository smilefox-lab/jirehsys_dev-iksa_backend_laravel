<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RealEstateChangeColumnsInLesseesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('re_lessees', function (Blueprint $table) {
            $table->string('rut', 12)->unique()->nullable()->change();
            $table->string('email')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('re_lessees', function (Blueprint $table) {
            $table->string('rut', 12)->change();
            $table->string('email')->change();
            $table->dropUnique(['rut']);
        });
    }
}
