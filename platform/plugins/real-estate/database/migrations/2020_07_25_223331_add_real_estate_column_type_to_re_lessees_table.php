<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRealEstateColumnTypeToReLesseesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('re_lessees', function (Blueprint $table) {
            $table->string('type', 120);
            $table->string('email')->change();
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
            $table->string('email')->change();
            $table->dropColumn('type');
        });
    }
}
