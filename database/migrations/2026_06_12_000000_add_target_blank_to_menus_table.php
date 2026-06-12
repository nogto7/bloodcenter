<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasColumn('menus', 'target_blank')) {
            return;
        }

        Schema::table('menus', function (Blueprint $table) {
            $table->boolean('target_blank')->default(false);
        });

        // Хуучин hard-coded зан төлвийг хадгална: "Шилэн данс" (id=5) гадаад
        // линкийг шинэ цонхонд нээдэг байсан.
        DB::table('menus')->where('id', 5)->update(['target_blank' => true]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasColumn('menus', 'target_blank')) {
            return;
        }

        Schema::table('menus', function (Blueprint $table) {
            $table->dropColumn('target_blank');
        });
    }
};
