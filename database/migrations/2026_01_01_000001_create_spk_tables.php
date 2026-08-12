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
        // 1. Table Peminatan
        Schema::create('peminatan', function (Blueprint $table) {
            $table->id();
            $table->string('kode')->unique(); // F1, F2, F3, F4, F5
            $table->string('nama'); // e.g. Sosial-Ekonomi & Teknologi Digital
            $table->text('deskripsi')->nullable();
            $table->string('daftar_kelas'); // e.g. "XI-1, XI-2, XI-3"
            $table->timestamps();
        });

        // 2. Table Mata Pelajaran
        Schema::create('mata_pelajaran', function (Blueprint $table) {
            $table->id();
            $table->string('kode')->unique();
            $table->string('nama');
            $table->enum('kategori', ['umum', 'pilihan'])->default('umum');
            $table->foreignId('peminatan_id')->nullable()->constrained('peminatan')->onDelete('cascade');
            $table->timestamps();
        });

        // 3. Table Nilai Rapor (Semester 1-5)
        Schema::create('nilai_rapor', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('mata_pelajaran_id')->constrained('mata_pelajaran')->onDelete('cascade');
            $table->integer('semester'); // 1..5
            $table->decimal('nilai', 5, 2); // 0-100
            $table->timestamps();
        });

        // 4. Table Kategori Kecerdasan Majemuk
        Schema::create('kategori_kecerdasan', function (Blueprint $table) {
            $table->id();
            $table->string('kode')->unique(); // K01..K08
            $table->string('nama'); // Linguistik, Logis-Matematika, Visual-Spasial, Kinestetik, Musikal, Interpersonal, Intrapersonal, Naturalis
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });

        // 5. Table Pertanyaan Kecerdasan
        Schema::create('pertanyaan_kecerdasan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kategori_kecerdasan_id')->constrained('kategori_kecerdasan')->onDelete('cascade');
            $table->text('pertanyaan');
            $table->timestamps();
        });

        // 6. Table Jawaban Kecerdasan Siswa
        Schema::create('jawaban_kecerdasan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('pertanyaan_kecerdasan_id')->constrained('pertanyaan_kecerdasan')->onDelete('cascade');
            $table->integer('skor'); // 1-5
            $table->timestamps();
        });

        // 7. Table Pertanyaan Minat Bakat
        Schema::create('pertanyaan_minat', function (Blueprint $table) {
            $table->id();
            $table->string('kategori_target'); // e.g., 'Ekonomi & Bisnis', 'Kesehatan', 'Teknik', etc.
            $table->text('pertanyaan');
            $table->timestamps();
        });

        // 8. Table Jawaban Minat Bakat Siswa
        Schema::create('jawaban_minat', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('pertanyaan_minat_id')->constrained('pertanyaan_minat')->onDelete('cascade');
            $table->integer('skor'); // 1-5
            $table->timestamps();
        });

        // 9. Table Rumpun Jurusan
        Schema::create('rumpun_jurusan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('peminatan_id')->constrained('peminatan')->onDelete('cascade');
            $table->string('nama'); // RUMPUN EKONOMI & BISNIS, RUMPUN KESEHATAN, dll
            $table->timestamps();
        });

        // 10. Table Jurusan Kuliah
        Schema::create('jurusan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rumpun_id')->constrained('rumpun_jurusan')->onDelete('cascade');
            $table->string('nama'); // Akuntansi, Manajemen, Kedokteran, dll
            $table->text('deskripsi')->nullable();
            $table->text('prospek_kerja')->nullable();
            $table->foreignId('kategori_kecerdasan_utama_id')->nullable()->constrained('kategori_kecerdasan')->nullOnDelete();
            $table->foreignId('mapel_utama_id')->nullable()->constrained('mata_pelajaran')->nullOnDelete();
            $table->timestamps();
        });

        // 11. Table Kriteria SAW
        Schema::create('kriteria_saw', function (Blueprint $table) {
            $table->id();
            $table->string('kode')->unique(); // C1, C2, C3
            $table->string('nama'); // C1: Nilai Rapor, C2: Kecerdasan Majemuk, C3: Minat Bakat
            $table->decimal('bobot', 5, 2); // e.g. 0.40, 0.30, 0.30 (Sum = 1.00 or 100%)
            $table->enum('jenis', ['benefit', 'cost'])->default('benefit');
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });

        // 12. Table Hasil SAW
        Schema::create('hasil_saw', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('jurusan_id')->constrained('jurusan')->onDelete('cascade');
            $table->decimal('skor_rapor', 8, 4);
            $table->decimal('skor_kecerdasan', 8, 4);
            $table->decimal('skor_minat', 8, 4);
            $table->decimal('nilai_v', 8, 4); // Preferensi SAW
            $table->integer('ranking');
            $table->json('detail_kalkulasi')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hasil_saw');
        Schema::dropIfExists('kriteria_saw');
        Schema::dropIfExists('jurusan');
        Schema::dropIfExists('rumpun_jurusan');
        Schema::dropIfExists('jawaban_minat');
        Schema::dropIfExists('pertanyaan_minat');
        Schema::dropIfExists('jawaban_kecerdasan');
        Schema::dropIfExists('pertanyaan_kecerdasan');
        Schema::dropIfExists('kategori_kecerdasan');
        Schema::dropIfExists('nilai_rapor');
        Schema::dropIfExists('mata_pelajaran');
        Schema::dropIfExists('peminatan');
    }
};
