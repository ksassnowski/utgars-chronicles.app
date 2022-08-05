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

use App\CardType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePeriodsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('periods', static function (Blueprint $table): void {
            $table->bigIncrements('id');
            $table->string('name');
            $table->enum('type', [CardType::Light->value, CardType::Dark->value]);
            $table->unsignedInteger('position')->default(1);
            $table->unsignedBigInteger('history_id');
            $table->timestamps();

            $table->foreign('history_id')->references('id')->on('histories')
                ->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('periods');
    }
}
