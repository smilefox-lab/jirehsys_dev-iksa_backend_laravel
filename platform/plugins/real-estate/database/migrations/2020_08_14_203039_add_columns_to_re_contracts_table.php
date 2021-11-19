<?php

use Botble\ACL\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToReContractsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('re_contracts', function (Blueprint $table) {
            $table->decimal('contribution_quota', 15, 2)->unsigned()->nullable();
            $table->decimal('contribution', 15, 2)->unsigned()->nullable();
            $table->decimal('income', 15, 2)->unsigned()->nullable();

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
            $table->dropColumn('income');
            $table->dropColumn('contribution');
            $table->dropColumn('contribution_quota');
        });
    }
}
