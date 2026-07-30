<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('keluargas', function (Blueprint $table) {
            $table->string('sumber_air_lainnya')->nullable()->after('sumber_air');
            $table->string('jenis_stiker')->nullable()->after('menempel_stiker_p4k');
        });

        Schema::table('anggota_keluargas', function (Blueprint $table) {
            $table->string('keterangan_tabungan')->nullable()->after('memiliki_tabungan');
        });
    }

    public function down(): void
    {
        Schema::table('keluargas', function (Blueprint $table) {
            $table->dropColumn(['sumber_air_lainnya', 'jenis_stiker']);
        });

        Schema::table('anggota_keluargas', function (Blueprint $table) {
            $table->dropColumn('keterangan_tabungan');
        });
    }
};
