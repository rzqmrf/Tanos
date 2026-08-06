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
        // 1. Tambah Kolom Detail Karyawan ke tabel employees
        Schema::table('employees', function (Blueprint $table) {
            $table->string('nipp')->nullable()->unique()->after('id');
            $table->string('bank_name')->nullable()->after('role');
            $table->string('bank_account_number')->nullable()->after('bank_name');
            $table->string('bank_account_name')->nullable()->after('bank_account_number');
            $table->string('ptkp_status')->default('TK/0')->after('bank_account_name');
            $table->date('tmt_date')->nullable()->after('ptkp_status');
            $table->string('bpjs_kesehatan_number')->nullable()->after('tmt_date');
            $table->string('bpjs_ketenagakerjaan_number')->nullable()->after('bpjs_kesehatan_number');
        });

        // 2. Buat tabel Divisions (Unit Kerja)
        Schema::create('divisions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // 3. Buat tabel Job Positions (Jabatan)
        Schema::create('job_positions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('division_id')->constrained('divisions')->onDelete('cascade');
            $table->string('code')->unique();
            $table->string('name');
            $table->timestamps();
        });

        // 4. Buat tabel Employee Movements / ECN (Histori Karir/Mutasi)
        Schema::create('employee_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            $table->enum('movement_type', ['New Hire', 'Promotion', 'Mutation', 'Demotion', 'Resignation'])->default('Mutation');
            $table->foreignId('from_position_id')->nullable()->constrained('job_positions')->onDelete('set null');
            $table->foreignId('to_position_id')->nullable()->constrained('job_positions')->onDelete('set null');
            $table->foreignId('from_project_id')->nullable()->constrained('projects')->onDelete('set null');
            $table->foreignId('to_project_id')->nullable()->constrained('projects')->onDelete('set null');
            $table->string('reference_number')->nullable(); // Nomor SK/ECN
            $table->date('effective_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_movements');
        Schema::dropIfExists('job_positions');
        Schema::dropIfExists('divisions');

        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn([
                'nipp',
                'bank_name',
                'bank_account_number',
                'bank_account_name',
                'ptkp_status',
                'tmt_date',
                'bpjs_kesehatan_number',
                'bpjs_ketenagakerjaan_number'
            ]);
        });
    }
};
