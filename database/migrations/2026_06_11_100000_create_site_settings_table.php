<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Production DB-д гараар үүсгэсэн хүснэгт байж болзошгүй тул idempotent байна
        if (!Schema::hasTable('site_settings')) {
            Schema::create('site_settings', function (Blueprint $table) {
                $table->id();
                $table->string('key')->unique();
                $table->text('value')->nullable();
                $table->timestamps();
            });
        }

        // Саналын хүргүүлэх салбарын эхлэлийн жагсаалт (админ дараа нь өөрчилнө)
        if (DB::table('site_settings')->where('key', 'feedback_positions')->doesntExist()) {
            DB::table('site_settings')->insert([
                'key' => 'feedback_positions',
                'value' => implode("\n", [
                    'Төв (Улаанбаатар)',
                    'Архангай аймаг дахь салбар',
                    'Баян-Өлгий аймаг дахь салбар',
                    'Баянхонгор аймаг дахь салбар',
                    'Булган аймаг дахь салбар',
                    'Говь-Алтай аймаг дахь салбар',
                    'Говьсүмбэр аймаг дахь салбар',
                    'Дархан-Уул аймаг дахь салбар',
                    'Дорноговь аймаг дахь салбар',
                    'Дорнод аймаг дахь салбар',
                    'Дундговь аймаг дахь салбар',
                    'Завхан аймаг дахь салбар',
                    'Орхон аймаг дахь салбар',
                    'Өвөрхангай аймаг дахь салбар',
                    'Өмнөговь аймаг дахь салбар',
                    'Сүхбаатар аймаг дахь салбар',
                    'Сэлэнгэ аймаг дахь салбар',
                    'Төв аймаг дахь салбар',
                    'Увс аймаг дахь салбар',
                    'Ховд аймаг дахь салбар',
                    'Хөвсгөл аймаг дахь салбар',
                    'Хэнтий аймаг дахь салбар',
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('site_settings');
    }
};
