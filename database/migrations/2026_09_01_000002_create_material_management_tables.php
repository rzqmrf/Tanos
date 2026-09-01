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
        Schema::dropIfExists('materials_outline_agreements');
        Schema::dropIfExists('materials_equipment');

        // 1. Materials Equipment
        Schema::create('materials_equipment', function (Blueprint $table) {
            $table->id();
            $table->string('equipment_code', 50)->unique();
            $table->string('name');
            $table->string('category', 100)->default('Heavy Equipment'); // Heavy Equipment, Vehicle, Power Tools, Survey Instrument, Safety Tool
            $table->string('brand_model', 100)->nullable();
            $table->string('serial_number', 100)->nullable();
            $table->foreignId('project_id')->nullable()->constrained('projects')->nullOnDelete();
            $table->enum('condition', ['Operational', 'Maintenance', 'Broken', 'Standby'])->default('Operational');
            $table->date('purchase_date')->nullable();
            $table->decimal('purchase_cost', 18, 2)->default(0.00);
            $table->date('last_service_date')->nullable();
            $table->date('next_service_date')->nullable();
            $table->date('certification_expiry')->nullable();
            $table->text('notes')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        // 2. Materials Outline Agreements
        Schema::create('materials_outline_agreements', function (Blueprint $table) {
            $table->id();
            $table->string('agreement_number', 50)->unique(); // e.g. OA-2026-001
            $table->foreignId('partner_id')->nullable()->constrained('partners')->nullOnDelete();
            $table->string('title');
            $table->enum('agreement_type', ['Quantity Contract', 'Value Contract'])->default('Value Contract');
            $table->decimal('target_value', 18, 2)->default(0.00);
            $table->string('currency', 10)->default('IDR');
            $table->date('start_date');
            $table->date('end_date');
            $table->enum('status', ['Draft', 'Active', 'Expired', 'Terminated'])->default('Active');
            $table->text('terms')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('materials_outline_agreements');
        Schema::dropIfExists('materials_equipment');
    }
};
