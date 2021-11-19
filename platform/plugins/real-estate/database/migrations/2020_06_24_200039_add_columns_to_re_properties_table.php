<?php

use Botble\ACL\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToRePropertiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('re_properties', function (Blueprint $table) {
            $table->dropColumn('type');
            $table->dropColumn('project_id');
            $table->dropColumn('period');
            $table->dropColumn('author_type');
            $table->dropColumn('moderation_status');
            $table->dropColumn('auto_renew');
            $table->dropColumn('never_expired');
            $table->dropColumn('category_id');
            $table->dropColumn('currency_id');
            $table->dropColumn('author_id');
            $table->dropColumn('content');
            $table->dropColumn('expire_date');
            $table->string('status', 60)->default('available')->change();
            $table->string('location')->change();
            $table->integer('city_id')->unsigned()->change();
            $table->decimal('price', 15, 2)->nullable()->change();
            $table->unsignedBigInteger('company_id')->nullable();
            $table->string('role');
            $table->json('coordinates')->after('location');
            $table->string('leaves')->nullable();
            $table->string('number')->nullable();
            $table->string('year')->nullable();
            $table->decimal('buy', 15, 2)->nullable();
            $table->date('date_deed')->nullable();
            $table->decimal('appraisal', 15, 2)->unsigned()->nullable();
            $table->decimal('uf', 15, 2)->unsigned()->nullable();
            $table->decimal('appraisal_coin', 15, 2)->unsigned()->nullable();
            $table->decimal('rent', 15, 2)->nullable();
            $table->decimal('contribution_quota', 15, 2)->unsigned()->nullable();
            $table->decimal('contribution', 15, 2)->unsigned()->nullable();
            $table->decimal('income', 15, 2)->unsigned()->nullable();
            $table->decimal('rent_cost', 15, 2)->unsigned()->nullable();
            $table->integer('square_build')->unsigned()->nullable()->after('square');
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
            $table->dropColumn('square_build');
            $table->dropColumn('rent_cost');
            $table->dropColumn('income');
            $table->dropColumn('contribution');
            $table->dropColumn('contribution_quota');
            $table->dropColumn('rent');
            $table->dropColumn('appraisal_coin');
            $table->dropColumn('uf');
            $table->dropColumn('appraisal');
            $table->dropColumn('date_deed');
            $table->dropColumn('buy');
            $table->dropColumn('year');
            $table->dropColumn('number');
            $table->dropColumn('leaves');
            $table->dropColumn('coordinates');
            $table->dropColumn('role');
            $table->dropColumn('company_id');
            $table->decimal('price', 15, 0)->nullable()->change();
            $table->integer('city_id')->unsigned()->nullable()->change();
            $table->string('location')->nullable()->change();
            $table->string('status', 60)->default('selling')->change();
            $table->date('expire_date')->nullable();
            $table->text('content')->nullable();
            $table->integer('author_id')->nullable();
            $table->integer('currency_id')->unsigned()->nullable();
            $table->integer('category_id')->unsigned()->nullable();
            $table->boolean('never_expired')->default(false);
            $table->boolean('auto_renew')->default(false);
            $table->string('moderation_status', 60)->default('pending');
            $table->string('author_type', 255)->default(addslashes(User::class));
            $table->string('period', 30)->default('month');
            $table->integer('project_id')->unsigned()->default(0);
            $table->string('type', 20)->default('sale');
        });
    }
}
