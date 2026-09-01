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
        Schema::create('project_master_data', function (Blueprint $table) {
            $table->id();
            $table->string('category')->index(); // feasibility_metrics, project_category, project_type, object_type, status, master_code, project_role, wbs_payroll_category
            $table->string('code')->nullable();
            $table->string('name');
            $table->string('uom')->nullable(); // For feasibility metrics
            $table->string('scope')->nullable(); // For object type
            $table->string('project_type')->nullable(); // For object type
            $table->integer('seq')->nullable(); // For status
            $table->string('coa')->nullable(); // For wbs payroll category
            $table->text('description')->nullable();
            $table->string('validity_start')->default('2024-01-01 00:00:00');
            $table->string('validity_end')->default('9999-12-31 00:00:00');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_master_data');
    }
};
