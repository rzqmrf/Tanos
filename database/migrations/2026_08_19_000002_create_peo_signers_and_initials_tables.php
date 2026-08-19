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
        Schema::create('peo_signers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('peo_setting_id')->constrained('peo_settings')->onDelete('cascade');
            $table->integer('no')->default(1);
            $table->enum('jenis_pihak', ['Internal', 'External'])->default('Internal');
            $table->string('kode_jabatan');
            $table->string('nama_jabatan');
            $table->string('nama_pegawai');
            $table->integer('pihak')->default(1);
            $table->integer('priority')->default(1);
            $table->timestamps();
        });

        Schema::create('peo_initials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('peo_setting_id')->constrained('peo_settings')->onDelete('cascade');
            $table->integer('no')->default(1);
            $table->enum('jenis_pihak', ['Internal', 'External'])->default('Internal');
            $table->string('kode_jabatan');
            $table->string('nama_jabatan');
            $table->string('nama_pegawai');
            $table->integer('priority')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peo_initials');
        Schema::dropIfExists('peo_signers');
    }
};
