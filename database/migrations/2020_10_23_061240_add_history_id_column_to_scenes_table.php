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
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddHistoryIdColumnToScenesTable extends Migration
{
    public function up(): void
    {
        Schema::table('scenes', static function (Blueprint $table): void {
            $table->foreignId('history_id')
                ->nullable()
                ->constrained('histories')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }
}
