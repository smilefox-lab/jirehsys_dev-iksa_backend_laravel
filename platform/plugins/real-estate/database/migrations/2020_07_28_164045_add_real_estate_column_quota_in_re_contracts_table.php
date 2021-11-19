<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRealEstateColumnQuotaInReContractsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('re_contracts', function (Blueprint $table) {
            $table->decimal('quota', 15, 0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('re_contracts', function (Blueprint $table) {
            $table->dropColumn('quota');
        });
    }
}
