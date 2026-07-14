<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('anggota_keluargas', function (Blueprint $table) {
            $table->string('pekerjaan')->nullable()->after('agama');
            $table->string('pendidikan')->nullable()->after('agama');
        });
    }

    public function down(): void
    {
        Schema::table('anggota_keluargas', function (Blueprint $table) {
            $table->dropColumn(['pekerjaan', 'pendidikan']);
        });
    }
};
