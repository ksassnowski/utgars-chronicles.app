<?php declare(strict_types=1);

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MakeNameColumnsIntoText extends Migration
{
    public function __construct()
    {
        // Weird bug fix around the fact that you can't modify columns
        // in a table that has *any* 'enum' columns...
        DB::getDoctrineSchemaManager()->getDatabasePlatform()->registerDoctrineTypeMapping('enum', 'string');
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('events', function (Blueprint $table) {
            $table->text('name')->change();
        });

        Schema::table('periods', function (Blueprint $table) {
            $table->text('name')->change();
        });
    }
}
