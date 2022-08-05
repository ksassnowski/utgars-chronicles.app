<?php

declare(strict_types=1);

/**
 * Copyright (c) 2022 Kai Sassnowski
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @see https://github.com/ksassnowski/utgars-chronicles.app
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class SetHistoryIdForAllEventsAndScenes extends Migration
{
    public function up(): void
    {
        $updateEventsQuery = <<<'SQL'
UPDATE `events`
   SET `history_id` =
       (SELECT history_id
          FROM `periods`
         WHERE `periods`.`id` = `events`.`period_id`);
SQL;

        $updateScenesQuery = <<<'SQL'
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
