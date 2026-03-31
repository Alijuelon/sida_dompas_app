<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('anggota_keluargas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('keluarga_id')->constrained()->onDelete('cascade');
            $table->string('nik', 16)->unique();
            $table->string('nama_anggota');
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->date('tanggal_lahir');
            $table->string('agama')->nullable();
            $table->string('pendidikan')->nullable();
            $table->string('pekerjaan')->nullable();
            $table->string('status_dalam_keluarga')->nullable(); // kepala keluarga, istri, anak, dll
            $table->string('status_perkawinan')->nullable(); // belum kawin, kawin, cerai hidup, cerai mati
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('anggota_keluargas');
    }
};
