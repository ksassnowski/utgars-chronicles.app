<?php declare(strict_types=1);

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;

class SetHistoryIdForAllEventsAndScenes extends Migration
{
    public function up(): void
    {
        $updateEventsQuery = <<<SQL
UPDATE `events`
   SET `history_id` =
       (SELECT history_id
          FROM `periods`
         WHERE `periods`.`id` = `events`.`period_id`);
SQL;

        $updateScenesQuery = <<<SQL
UPDATE `scenes`
   SET `history_id` =
       (SELECT `history_id`
          FROM `periods`
         WHERE `periods`.`id` =
               (SELECT `period_id`
                  FROM `events`
                 WHERE `events`.`id` = `scenes`.`event_id`));
SQL;

        DB::statement($updateEventsQuery);
        DB::statement($updateScenesQuery);
    }
}
