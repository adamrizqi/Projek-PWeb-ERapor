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
        Schema::create('kelas', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kelas', 10); // Misal: 1A, 2B, dst
            $table->integer('tingkat'); // 1-6 untuk SD
            $table->string('tahun_ajaran', 9); // 2024/2025
            $table->unsignedBigInteger('wali_kelas_id')->nullable();
            $table->timestamps();

            $table->foreign('wali_kelas_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kelas');
    }
};
