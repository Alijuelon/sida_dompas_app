<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kaders', function (Blueprint $table) {
            $table->string('rt', 10)->nullable()->after('wilayah');
            $table->string('rw', 10)->nullable()->after('rt');
        });
    }

    public function down(): void
    {
        Schema::table('kaders', function (Blueprint $table) {
            $table->dropColumn(['rt', 'rw']);
        });
    }
};
