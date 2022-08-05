<?php

use App\AgentPowers;
use App\History;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('echo_game_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(History::class)
                ->constrained()
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->text('faction_1')->nullable();
            $table->text('faction_2')->nullable();
            $table->enum('agent_powers', [
                AgentPowers::Ordinary,
                AgentPowers::Extraordinary,
                AgentPowers::Omnipotent
            ])->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('echo_game_settings');
    }
};
