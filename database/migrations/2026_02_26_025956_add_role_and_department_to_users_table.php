<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // role аль хэдийн байгаа эсэхийг шалгах
            if (!Schema::hasColumn('users', 'role')) {
                $table->enum('role', ['admin','editor','publisher'])
                      ->default('editor')
                      ->after('email');
            }

            // department_id аль хэдийн байгаа эсэхийг шалгах
            if (!Schema::hasColumn('users', 'department_id')) {
                $table->unsignedBigInteger('department_id')->nullable()->after('role');
                $table->foreign('department_id')
                      ->references('id')
                      ->on('departments')
                      ->onDelete('set null');
            }
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'department_id')) {
                $table->dropForeign(['department_id']);
                $table->dropColumn('department_id');
            }
            // role-г устгах шаардлагагүй, production-ийг хадгалах
            // if (Schema::hasColumn('users', 'role')) {
            //     $table->dropColumn('role');
            // }
        });
    }
};