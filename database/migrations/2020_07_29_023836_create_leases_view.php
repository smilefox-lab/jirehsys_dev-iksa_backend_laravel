<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeasesView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement($this->dropView());

        DB::statement($this->createView());
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement($this->dropView());
    }

    private function dropView(): string
    {
        return <<<SQL
DROP VIEW IF EXISTS `leases_view`;
SQL;

    }

    private function createView(): string
    {
        return <<<SQL
CREATE VIEW `leases_view` AS

SELECT `c`.`id` `company_id`, `c`.`name` `company_name`, `rep`.`id` `property_id`, `rep`.`name` `property_name`, `rep`.`type_id` ,`rec`.`id` `contract_id`, `rec`.`name` `contract_name`, `rec`.`quota`, `rec`.`cutoff_date`, `repay`.`id`, `repay`.`amount`, `repay`.`date` FROM `companies` `c`
    LEFT JOIN `re_properties` `rep`
        ON `c`.`id` = `rep`.`company_id`
    LEFT JOIN `re_types` `ret`
        ON `rep`.`type_id` = `ret`.`id`
    LEFT JOIN `re_contracts` `rec`
        ON `rep`.`id` = `rec`.`property_id`
    LEFT JOIN `re_payments` `repay`
        ON `rec`.`id` = `repay`.`contract_id`
SQL;

    }

}
