<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RealEstateChangeColumnStatusInReTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('re_types', function (Blueprint $table) {
            $table->string('status', 60)->default('enabled')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('re_types', function (Blueprint $table) {
            $table->string('status', 60)->default('published')->change();
        });
    }
}
