<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('group_items')) {
            return;
        }

        Schema::table('group_items', function (Blueprint $table) {
            if (!Schema::hasColumn('group_items', 'date')) {
                $table->date('date')->nullable()->after('title');
            }
            if (!Schema::hasColumn('group_items', 'file_id')) {
                $table->unsignedBigInteger('file_id')->nullable();
            }
            if (!Schema::hasColumn('group_items', 'link')) {
                $table->string('link')->nullable();
            }
            if (!Schema::hasColumn('group_items', 'content')) {
                $table->text('content')->nullable();
            }
            if (!Schema::hasColumn('group_items', 'sort')) {
                $table->integer('sort')->default(0);
            }
        });
    }

    public function down(): void
    {
        // No-op: patches drifted production schema only.
    }
};
