<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (! Schema::hasTable('employees')) {
            return;
        }

        if (! Schema::hasColumn('employees', 'order')) {
            Schema::table('employees', function (Blueprint $table) {
                $table->integer('order')->default(0)->after('email');
            });
        }
    }

    public function down(): void
    {
    }
};
