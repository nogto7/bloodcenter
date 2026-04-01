<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('news', function (Blueprint $table) {
            // // 1. constraint устгах
            // $table->dropForeign(['menu_id']); // эсвэл $table->dropForeign('news_menu_id_foreign');
        
            // // 2. column устгах
            // $table->dropColumn('menu_id');
        
            // // 3. highlight_image нэмэх
            // $table->string('highlight_image')->nullable()->after('image');

            if (Schema::hasColumn('news', 'menu_id')) {
                $table->dropColumn('menu_id');
            }
        });
        
    }

    public function down(): void
    {
        Schema::table('news', function (Blueprint $table) {
            $table->unsignedBigInteger('menu_id')->nullable()->after('id');
            $table->foreign('menu_id')->references('id')->on('menus')->onDelete('cascade');
            $table->dropColumn('highlight_image');
        });
    }
};

