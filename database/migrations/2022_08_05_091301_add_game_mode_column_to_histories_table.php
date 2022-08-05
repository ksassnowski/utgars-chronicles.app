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

use App\MicroscopeGameMode;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public function up(): void
    {
        Schema::table('histories', static function (Blueprint $table): void {
            $table->after('name', static function (Blueprint $table): void {
                $table->enum('game_mode', [
                    MicroscopeGameMode::BaseGame->value,
                    MicroscopeGameMode::Echo->value,
                ])->default(MicroscopeGameMode::BaseGame->value);
            });
        });
    }

    public function down(): void
    {
        Schema::table('histories', static function (Blueprint $table): void {
            $table->dropColumn('game_mode');
        });
    }
};
