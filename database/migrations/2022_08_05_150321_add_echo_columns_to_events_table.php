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

use App\Event;
use App\EventType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public function up(): void
    {
        Schema::table('events', static function (Blueprint $table): void {
            $table->after('position', static function (Blueprint $table): void {
                $table->unsignedSmallInteger('echo_group')->nullable();
                $table->unsignedSmallInteger('echo_group_position')->default(1);
                $table->enum('event_type', [
                    EventType::Event->value,
                    EventType::Intervention->value,
                    EventType::Echo->value,
                ])->default(EventType::Echo->value);
                $table->boolean('contradiction')->default(false);
                $table->foreignIdFor(Event::class)
                    ->nullable()
                    ->constrained()
                    ->cascadeOnUpdate()
                    ->cascadeOnDelete();
            });
        });
    }
};
