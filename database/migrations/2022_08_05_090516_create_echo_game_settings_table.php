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

use App\AgentPowers;
use App\History;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public function up(): void
    {
        Schema::create('echo_game_settings', static function (Blueprint $table): void {
            $table->id();
            $table->foreignIdFor(History::class)
                ->constrained()
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->text('faction_1')->nullable();
            $table->text('faction_2')->nullable();
            $table->enum('agent_powers', [
                AgentPowers::Ordinary->value,
                AgentPowers::Extraordinary->value,
                AgentPowers::Omnipotent->value,
            ])->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('echo_game_settings');
    }
};
