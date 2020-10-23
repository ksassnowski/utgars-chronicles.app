<?php declare(strict_types=1);

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPublicColumnToHistoriesColumn extends Migration
{
    public function up(): void
    {
        Schema::table('histories', function (Blueprint $table) {
            $table->boolean('public')->default(false);
        });
    }
}
