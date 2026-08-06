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
        Schema::create('wbs_elements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('projects')->onDelete('cascade');
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->string('wbs_code');
            $table->string('wbs_name');
            $table->string('wbs_category');
            $table->integer('weight')->default(0);
            $table->date('expected_start')->nullable();
            $table->date('expected_end')->nullable();
            $table->boolean('sent_to_sap')->default(false);
            $table->timestamps();

            $table->foreign('parent_id')->references('id')->on('wbs_elements')->onDelete('cascade');
        });

        Schema::create('payroll_periods', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('projects')->onDelete('cascade');
            $table->string('name');
            $table->enum('type', ['On-Cycle', 'Off-Cycle'])->default('On-Cycle');
            $table->string('month');
            $table->date('start_date');
            $table->date('end_date');
            $table->enum('status', ['Draft', 'Simulated', 'Completed', 'Posted', 'Voided'])->default('Draft');
            $table->timestamps();
        });

        Schema::create('payroll_components', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payroll_period_id')->constrained('payroll_periods')->onDelete('cascade');
            $table->foreignId('wbs_element_id')->nullable()->constrained('wbs_elements')->onDelete('set null');
            $table->string('code');
            $table->string('name');
            $table->enum('type', ['Valuation', 'Formula'])->default('Valuation');
            $table->decimal('amount', 15, 2)->default(0.00);
            $table->string('formula_expression')->nullable();
            $table->timestamps();
        });

        Schema::create('payroll_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payroll_period_id')->constrained('payroll_periods')->onDelete('cascade');
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            $table->integer('days_present')->default(0);
            $table->decimal('overtime_hours', 8, 2)->default(0.00);
            $table->decimal('basic_salary', 15, 2)->default(0.00);
            $table->decimal('transport_allowance', 15, 2)->default(0.00);
            $table->decimal('overtime_pay', 15, 2)->default(0.00);
            $table->decimal('deductions', 15, 2)->default(0.00);
            $table->decimal('net_salary', 15, 2)->default(0.00);
            $table->string('sap_doc_number')->nullable();
            $table->timestamp('posted_at')->nullable();
            $table->timestamps();
        });

        Schema::create('pranota_billings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payroll_period_id')->nullable()->constrained('payroll_periods')->onDelete('set null');
            $table->foreignId('project_id')->constrained('projects')->onDelete('cascade');
            $table->string('pranota_number')->unique();
            $table->decimal('amount', 15, 2)->default(0.00);
            $table->enum('status', ['Belum Terbilling', 'Siap Terbilling', 'Sudah Terbilling'])->default('Belum Terbilling');
            $table->timestamps();
        });

        Schema::create('nota_billings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('projects')->onDelete('cascade');
            $table->string('nota_number')->unique();
            $table->decimal('amount', 15, 2)->default(0.00);
            $table->string('tax_code')->default('PPN 11%');
            $table->enum('status', ['Draft', 'Completed'])->default('Draft');
            $table->string('sap_doc_number')->nullable();
            $table->timestamp('posted_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nota_billings');
        Schema::dropIfExists('pranota_billings');
        Schema::dropIfExists('payroll_results');
        Schema::dropIfExists('payroll_components');
        Schema::dropIfExists('payroll_periods');
        Schema::dropIfExists('wbs_elements');
    }
};
