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
        Schema::create('nilai', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('siswa_id');
            $table->unsignedBigInteger('mata_pelajaran_id');
            $table->integer('semester'); // 1 atau 2
            $table->string('tahun_ajaran', 9);
            $table->integer('nilai_pengetahuan')->nullable();
            $table->integer('nilai_keterampilan')->nullable();
            $table->integer('nilai_akhir')->nullable(); // rata-rata
            $table->char('predikat', 1)->nullable(); // A, B, C, D
            $table->text('deskripsi')->nullable();
            $table->timestamps();

            $table->foreign('siswa_id')->references('id')->on('siswa')->onDelete('cascade');
            $table->foreign('mata_pelajaran_id')->references('id')->on('mata_pelajaran')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nilai');
    }
};
