<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RealEstateCreateLesseesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('re_lessees', function (Blueprint $table) {
            $table->id();
            $table->string('name', 120);
            $table->string('rut', 12);
            $table->string('email')->unique();
            $table->string('phone', 25)->nullable();
            $table->string('status', 60)->default('enabled');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('re_lessees');
    }
}
