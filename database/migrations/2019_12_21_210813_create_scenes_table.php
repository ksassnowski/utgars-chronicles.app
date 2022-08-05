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

class CreateScenesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('scenes', static function (Blueprint $table): void {
            $table->bigIncrements('id');
            $table->text('question');
            $table->text('scene')->nullable();
            $table->text('answer')->nullable();
            $table->unsignedInteger('position');
            $table->enum('type', [CardType::Light->value, CardType::Dark->value])->nullable();
            $table->unsignedBigInteger('event_id');
            $table->timestamps();

            $table->foreign('event_id')->references('id')->on('events')
                ->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scenes');
    }
}
