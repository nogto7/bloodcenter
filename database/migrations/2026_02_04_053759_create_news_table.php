<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('news', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('excerpt')->nullable();
            $table->longText('content');
            $table->string('image')->nullable();
            $table->string('highlight_image')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('highlight')->default(false);
            $table->timestamp('publish_at')->nullable();

            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete(); // User foreign key
            $table->foreignId('menu_id')->nullable()->constrained()->nullOnDelete(); // Menu foreign key

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('news');
    }
};
