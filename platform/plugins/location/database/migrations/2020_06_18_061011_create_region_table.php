<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateRegionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('regions', function (Blueprint $table) {
            $table->id();
            $table->string('name', 120);
            $table->integer('country_id')->unsigned()->nullable();
            $table->string('ordinal_symbol', 6);
            $table->tinyInteger('order')->default(0);
            $table->tinyInteger('is_default')->unsigned()->default(0);
            $table->string('status', 60)->default('enabled');
            $table->timestamps();
        });

        Schema::create('communes', function (Blueprint $table) {
            $table->id();
            $table->string('name', 120);
            $table->integer('region_id')->unsigned()->nullable();
            $table->tinyInteger('order')->default(0);
            $table->tinyInteger('is_default')->unsigned()->default(0);
            $table->string('status', 60)->default('enabled');
            $table->timestamps();
        });

        Schema::table('countries', function (Blueprint $table) {
            $table->string('nationality', 120)->nullable()->change();
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
        Schema::table('countries', function (Blueprint $table) {
            $table->string('status', 60)->default('published')->change();
            $table->string('nationality', 120)->change();
        });

        Schema::dropIfExists('regions');
        Schema::dropIfExists('communes');
    }
}
