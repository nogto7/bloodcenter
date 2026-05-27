<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (! Schema::hasTable('groups')) {
            return;
        }

        $idCol = collect(DB::select('SHOW COLUMNS FROM `groups` WHERE Field = ?', ['id']))->first();
        if (! $idCol) {
            return;
        }

        $isAutoInc = str_contains(strtolower($idCol->Extra ?? ''), 'auto_increment');
        if ($isAutoInc) {
            return;
        }

        $hasPrimary = collect(DB::select('SHOW KEYS FROM `groups` WHERE Key_name = ?', ['PRIMARY']))->isNotEmpty();
        if (! $hasPrimary) {
            DB::statement('ALTER TABLE `groups` ADD PRIMARY KEY (`id`)');
        }

        DB::statement('ALTER TABLE `groups` MODIFY `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT');
    }

    public function down(): void
    {
    }
};
