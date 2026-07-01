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
            $table->foreignId('keluarga_id')->constrained('keluargas')->onDelete('cascade');
            $table->string('no_reg', 50)->nullable();
            $table->string('nik', 16)->unique();
            $table->string('nama_anggota');
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir');
            $table->integer('umur')->nullable();
            $table->string('agama')->nullable();
            $table->string('pendidikan_terakhir')->nullable(); 
            $table->string('pekerjaan_utama')->nullable(); 
            $table->string('status_dalam_keluarga')->nullable(); 
            $table->string('status_perkawinan')->nullable(); 
            $table->string('dasa_wisma')->nullable();
            $table->string('nama_kepala_rumah_tangga')->nullable();
            
            // Kolom Tambahan Form 2
            $table->string('jabatan')->nullable();
            $table->string('alamat_jalan')->nullable();
            $table->string('rt', 10)->nullable();
            $table->string('rw', 10)->nullable();
            $table->string('desa_kelurahan')->default('Dompas');
            $table->string('kecamatan')->default('Bukit Batu');
            $table->string('kabupaten_kota')->default('Bengkalis');
            $table->string('provinsi')->default('Riau');

            // Program Sosial & PKK (Form 2)
            $table->boolean('akseptor_kb')->default(false);
            $table->string('jenis_akseptor_kb')->nullable();
            
            $table->boolean('aktif_posyandu')->default(false);
            $table->string('frekuensi_posyandu')->nullable();
            
            $table->boolean('ikut_bina_keluarga_balita')->default(false);
            $table->boolean('memiliki_tabungan')->default(false);
            
            $table->boolean('ikut_kelompok_belajar')->default(false);
            $table->string('jenis_paket_belajar')->nullable();
            
            $table->boolean('ikut_paud_sejenis')->default(false);
            
            $table->boolean('ikut_koperasi')->default(false);
            $table->string('jenis_koperasi')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('anggota_keluargas');
    }
};
