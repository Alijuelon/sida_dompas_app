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
        Schema::table('dasawismas', function (Blueprint $table) {
            $table->string('dusun')->nullable()->after('desa');
            $table->string('kategori')->nullable()->after('dusun')->comment('murni atau lestari');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dasawismas', function (Blueprint $table) {
            $table->dropColumn(['dusun', 'kategori']);
        });
    }
};

