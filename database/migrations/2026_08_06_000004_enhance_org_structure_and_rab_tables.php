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
        // 1. Buat Tabel rab_budgets
        Schema::create('rab_budgets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('projects')->onDelete('cascade');
            $table->string('document_number')->unique();
            $table->string('name');
            $table->integer('year');
            $table->decimal('total_revenue', 15, 2)->default(0.00);
            $table->decimal('total_cost', 15, 2)->default(0.00);
            $table->enum('sap_status', ['Draft', 'Sent'])->default('Draft');
            $table->enum('doc_status', ['Draft', 'Approved', 'Voided'])->default('Draft');
            $table->timestamps();
        });

        // 2. Buat Tabel rab_budget_items
        Schema::create('rab_budget_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rab_budget_id')->constrained('rab_budgets')->onDelete('cascade');
            $table->string('coa_code');
            $table->string('fund_center')->nullable();
            $table->string('cost_center')->nullable();
            $table->string('profit_center')->nullable();
            
            // Monthly budgeting columns
            $table->decimal('jan', 15, 2)->default(0.00);
            $table->decimal('feb', 15, 2)->default(0.00);
            $table->decimal('mar', 15, 2)->default(0.00);
            $table->decimal('apr', 15, 2)->default(0.00);
            $table->decimal('may', 15, 2)->default(0.00);
            $table->decimal('jun', 15, 2)->default(0.00);
            $table->decimal('jul', 15, 2)->default(0.00);
            $table->decimal('aug', 15, 2)->default(0.00);
            $table->decimal('sep', 15, 2)->default(0.00);
            $table->decimal('oct', 15, 2)->default(0.00);
            $table->decimal('nov', 15, 2)->default(0.00);
            $table->decimal('dec', 15, 2)->default(0.00);
            
            $table->decimal('total_amount', 15, 2)->default(0.00);
            $table->timestamps();
        });

        // 3. Tambah kolom STO Chart ke tabel divisions
        Schema::table('divisions', function (Blueprint $table) {
            $table->string('code')->nullable()->after('id');
            $table->unsignedBigInteger('parent_id')->nullable()->after('name');
            $table->string('regional')->nullable()->after('description');
            $table->string('cost_center')->nullable()->after('regional');
            $table->string('unit_type')->default('Perusahaan User')->after('cost_center');
            $table->date('valid_from')->nullable()->after('unit_type');
            $table->date('valid_to')->nullable()->after('valid_from');
            $table->boolean('active')->default(true)->after('valid_to');
            $table->boolean('sent_to_sap')->default(false)->after('active');

            $table->foreign('parent_id')->references('id')->on('divisions')->onDelete('set null');
        });

        // 4. Tambah kolom Job Formation ke tabel job_positions
        Schema::table('job_positions', function (Blueprint $table) {
            $table->unsignedBigInteger('parent_id')->nullable()->after('division_id');
            $table->string('regional')->nullable()->after('name');
            $table->boolean('is_leader')->default(false)->after('regional');
            $table->string('cost_center')->nullable()->after('is_leader');
            $table->string('cost_center_name')->nullable()->after('cost_center');
            $table->date('valid_from')->nullable()->after('cost_center_name');
            $table->date('valid_to')->nullable()->after('valid_from');
            $table->boolean('active')->default(true)->after('valid_to');
            $table->boolean('no_contract')->default(false)->after('active');
            $table->boolean('non_formation')->default(false)->after('no_contract');
            $table->boolean('sent_to_sap')->default(false)->after('non_formation');

            $table->foreign('parent_id')->references('id')->on('job_positions')->onDelete('set null');
        });

        // 5. Tambah kolom ECN ke tabel employee_movements
        Schema::table('employee_movements', function (Blueprint $table) {
            $table->string('ecn_name')->nullable()->after('id');
            $table->enum('status', ['Draft', 'Completed'])->default('Draft')->after('movement_type');
            $table->date('valid_from')->nullable()->after('effective_date');
            $table->date('valid_to')->nullable()->after('valid_from');
            $table->boolean('sent_to_sap')->default(false)->after('valid_to');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employee_movements', function (Blueprint $table) {
            $table->dropColumn(['ecn_name', 'status', 'valid_from', 'valid_to', 'sent_to_sap']);
        });

        Schema::table('job_positions', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
            $table->dropColumn([
                'parent_id', 'regional', 'is_leader', 'cost_center', 'cost_center_name', 
                'valid_from', 'valid_to', 'active', 'no_contract', 'non_formation', 'sent_to_sap'
            ]);
        });

        Schema::table('divisions', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
            $table->dropColumn([
                'code', 'parent_id', 'regional', 'cost_center', 'unit_type', 
                'valid_from', 'valid_to', 'active', 'sent_to_sap'
            ]);
        });

        Schema::dropIfExists('rab_budget_items');
        Schema::dropIfExists('rab_budgets');
    }
};
