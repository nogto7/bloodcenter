<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('files')) {
            return;
        }

        Schema::table('files', function (Blueprint $table) {
            if (!Schema::hasColumn('files', 'menu_id')) {
                $table->unsignedBigInteger('menu_id')->nullable()->after('folder_id');
            }
            if (!Schema::hasColumn('files', 'date')) {
                $table->date('date')->nullable();
            }
            if (!Schema::hasColumn('files', 'number')) {
                $table->string('number')->nullable();
            }
        });
    }

    public function down(): void
    {
        // No-op: this migration only patches drifted production schema.
    }
};
