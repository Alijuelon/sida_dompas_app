<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('keluargas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dasawisma_id')->constrained('dasawismas')->onDelete('cascade');
            $table->string('no_kk', 16)->unique();
            $table->string('nama_kepala_keluarga');
            $table->string('rt', 10)->nullable();
            $table->string('rw', 10)->nullable();
            $table->string('dusun_lingkungan')->nullable();
            $table->string('desa')->default('Dompas');
            $table->string('kecamatan')->default('Bukit Batu');
            $table->string('kabupaten')->default('Bengkalis');
            $table->string('provinsi')->default('Riau');
            
            // Rekapitulasi (Form 1 & 3)
            $table->integer('jumlah_anggota')->default(0);
            $table->integer('jumlah_laki_laki')->default(0);
            $table->integer('jumlah_perempuan')->default(0);
            
            $table->integer('jumlah_kk')->default(1);
            $table->integer('jumlah_balita')->default(0);
            $table->integer('jumlah_balita_laki')->default(0);
            $table->integer('jumlah_balita_perempuan')->default(0);
            $table->integer('jumlah_pus')->default(0);
            $table->integer('jumlah_wus')->default(0);
            $table->integer('jumlah_buta')->default(0);
            $table->integer('jumlah_berkebutuhan_khusus')->default(0);
            $table->integer('jumlah_ibu_hamil')->default(0);
            $table->integer('jumlah_ibu_menyusui')->default(0);
            $table->integer('jumlah_lansia')->default(0);

            // Kriteria Rumah (Form 3)
            $table->boolean('sehat_layak_huni')->default(false);
            $table->boolean('memiliki_tempat_sampah')->default(false);
            $table->boolean('memiliki_spal')->default(false);
            $table->boolean('menempel_stiker_p4k')->default(false);
            
            // Sumber Air & Makanan
            $table->string('sumber_air')->nullable();
            $table->string('makanan_pokok')->nullable();

            // Kegiatan (Form 3)
            $table->boolean('ikut_up2k')->default(false);
            $table->boolean('ikut_pekarangan')->default(false);
            $table->boolean('ikut_industri')->default(false);
            $table->boolean('ikut_kerja_bakti')->default(false);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('keluargas');
    }
};
