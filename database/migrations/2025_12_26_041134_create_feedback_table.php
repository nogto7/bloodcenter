<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('feedback', function (Blueprint $table) {
            $table->id();

            // form-н баганууд
            $table->string('name', 50);                     // Нэр
            $table->string('phone', 20);                     // Утасны дугаар
            $table->string('email', 100)->unique();          // И-мэйл
            $table->string('feedback_type', 100)->nullable();    // Санал хүсэлтийн төрөл
            $table->string('feedback_position', 100)->nullable();    // Салбар
            $table->string('message', 500)->nullable();     // Илгээх мэдээлэл

            // Laravel-н заавал байх баганууд
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feedback');
    }
};
