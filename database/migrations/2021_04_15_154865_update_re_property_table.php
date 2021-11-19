<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateRePropertyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('re_properties', function (Blueprint $table) {
          $table->decimal('square', 15, 2)->unsigned()->change();
          $table->decimal('square_build', 15, 2)->unsigned()->change();
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
          $table->integer('square')->unsigned()->change();
          $table->integer('square_build')->unsigned()->change();
        });
    }
}
