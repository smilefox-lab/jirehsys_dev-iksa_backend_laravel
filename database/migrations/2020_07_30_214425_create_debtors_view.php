<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDebtorsView extends Migration
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
DROP VIEW IF EXISTS `debtors_view`;
SQL;

    }

    private function createView(): string
    {
        return <<<SQL
CREATE VIEW `debtors_view` AS

SELECT `c`.`id` `company_id`, `c`.`name` `company_name`, `rl`.`id` `lessee_id`, `rl`.`name` `lessee_name`, `rl`.`rut` `lessee_rut`, `rep`.`id` `property_id`, `rep`.`name` `property_name`, `rep`.`type_id`  `property_type`, `rep`.`appraisal`, `rc`.`id` `contract_id`,  `rc`.`name` `contract_name`, `rc`.`start_date`, `rc`.`end_date`, `rc`.`quota` `expected`, 0 `paid`, `payment_date`, DAY(`cutoff`) `cutoff_day`, IF (DATEDIFF(`cutoff`, `payment_date`) < 30, TIMESTAMPDIFF(MONTH, `payment_date`, `cutoff`), TIMESTAMPDIFF(MONTH, `payment_date`, `cutoff`)) `months`, IF (DATEDIFF(`cutoff`, `payment_date`) > 15 AND DATEDIFF(`cutoff`, `payment_date`) < 30, "Retraso", IF (DATEDIFF(`cutoff`, `payment_date`) >= 30, "Mora", null)) `status` FROM `re_contracts` `rc` INNER JOIN (SELECT `p`.`contract_id`, MAX(`p`.`date`) as `payment_date` FROM `re_payments` `p` GROUP BY `p`.`contract_id` HAVING DATE(MAX(`p`.`date`)) < (SELECT DATE(`c`.`end_date`) FROM `re_contracts` `c` WHERE `c`.`id` = `p`.`contract_id`) AND (SELECT TIMESTAMPDIFF(MONTH, `c`.`start_date`, DATE(MAX(`p`.`date`))) FROM `re_contracts` `c` WHERE c.id = p.contract_id) < 11) `p1` ON `rc`.`id` = `p1`.`contract_id` INNER JOIN (SELECT `c`.`id`, DATE(CONCAT_WS('-', YEAR(NOW()), MONTH(NOW()), DAY(`c`.`cutoff_date`))) as `cutoff` FROM `re_contracts` `c`) `c2` ON `c2`.`id` = `rc`.`id` LEFT JOIN `re_lessees` `rl` ON `rl`.`id` = `rc`.`lessee_id` LEFT JOIN `re_properties` `rep` ON `rep`.`id` = `rc`.`property_id` LEFT JOIN `companies` `c` ON `c`.`id` = `rep`.`company_id`

UNION ALL

SELECT `c`.`id` `company_id`, `c`.`name` `company_name`, `rl`.`id` `lessee_id`, `rl`.`name` `lessee_name`, `rl`.`rut` `lessee_rut`, `rep`.`id` `property_id`, `rep`.`name` `property_name`, `rep`.`type_id`  `property_type`, `rep`.`appraisal`, `rc`.`id` `contract_id`,  `rc`.`name` `contract_name`, `rc`.`start_date`, `rc`.`end_date`, `rc`.`quota` `expected`, `rpa`.`amount` `paid`, `rpa`.`date` `payment_date`, DAY(`rpa`.`date`) `cutoff_day`, 0 `months`, "Pagado" `status` FROM `re_payments` `rpa` LEFT JOIN `re_contracts` `rc` ON `rpa`.`contract_id` = `rc`.`id` LEFT JOIN `re_lessees` `rl` ON `rl`.`id` = `rc`.`lessee_id` LEFT JOIN `re_properties` `rep` ON `rep`.`id` = `rc`.`property_id` LEFT JOIN `companies` `c`ON `c`.`id` = `rep`.`company_id`
SQL;

    }
}
