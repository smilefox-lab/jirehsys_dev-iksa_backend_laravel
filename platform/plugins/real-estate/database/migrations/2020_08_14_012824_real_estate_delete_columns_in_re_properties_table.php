<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RealEstateDeleteColumnsInRePropertiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('re_properties', function (Blueprint $table) {
            $table->dropColumn('contribution');
            $table->dropColumn('contribution_quota');
            $table->dropColumn('rent');
            $table->dropColumn('income');
            $table->renameColumn('appraisal_coin', 'pesos');
            $table->renameColumn('rent_cost', 'profitability');
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
            $table->renameColumn('pesos', 'appraisal_coin');
            $table->renameColumn('profitability', 'rent_cost');
            $table->decimal('income', 15, 2)->unsigned()->nullable();
            $table->decimal('rent', 15, 2)->nullable();
            $table->decimal('contribution_quota', 15, 2)->unsigned()->nullable();
            $table->decimal('contribution', 15, 2)->unsigned()->nullable();
        });
    }
}
