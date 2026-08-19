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
        Schema::create('peo_settings', function (Blueprint $table) {
            $table->id();
            $table->string('document_type')->default('Berita Acara');
            $table->string('customer');
            $table->string('project_name');
            $table->string('project_code');
            $table->enum('tab_category', ['Berita Acara', 'Surat Keluar'])->default('Berita Acara');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peo_settings');
    }
};
