<?php declare(strict_types=1);

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddHistoryIdColumnToScenesTable extends Migration
{
    public function up(): void
    {
        Schema::table('scenes', function (Blueprint $table) {
            $table->foreignId('history_id')
                ->nullable()
                ->constrained('histories')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }
}
