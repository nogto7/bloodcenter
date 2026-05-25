<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('group_items')) {
            return;
        }

        if (!Schema::hasColumn('group_items', 'order')) {
            return;
        }

        // Legacy column from a pre-Laravel schema. Laravel inserts never
        // populate it, so any NOT NULL definition without a default blocks
        // every new row. Make it nullable with a default of 0.
        DB::statement('ALTER TABLE `group_items` MODIFY `order` INT NULL DEFAULT 0');
    }

    public function down(): void
    {
        // No-op: patches drifted production schema only.
    }
};
