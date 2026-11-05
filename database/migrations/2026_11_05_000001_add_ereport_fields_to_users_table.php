<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('no_whatsapp')->default('');
            $table->text('alamat')->nullable();
            $table->string('foto')->nullable();
            $table->enum('role', ['warga', 'admin'])->default('warga')->index();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['role']);
            $table->dropColumn(['no_whatsapp', 'alamat', 'foto', 'role']);
        });
    }
};
