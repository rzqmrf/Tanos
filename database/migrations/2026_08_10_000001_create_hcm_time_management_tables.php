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
        // 1. Tabel absent_types
        Schema::create('absent_types', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->string('gender')->default('All'); // Male, Female, All
            $table->integer('priority_level')->default(1);
            $table->string('deduction_absent')->default('No'); // Yes, No
            $table->date('valid_from')->nullable();
            $table->date('valid_to')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        // 2. Tabel schedule_groups
        Schema::create('schedule_groups', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('type', ['Reguler', 'Shift', 'Manual Shift'])->default('Reguler');
            $table->time('work_start')->nullable();
            $table->time('work_end')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 3. Tabel schedule_assignments
        Schema::create('schedule_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            $table->foreignId('schedule_group_id')->constrained('schedule_groups')->onDelete('cascade');
            $table->date('valid_from');
            $table->date('valid_to');
            $table->timestamps();
        });

        // 4. Tabel time_evaluations
        Schema::create('time_evaluations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->date('valid_from');
            $table->date('valid_to');
            $table->integer('late_tolerance_minutes')->default(15);
            $table->integer('early_departure_minutes')->default(15);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 5. Tabel time_periods
        Schema::create('time_periods', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('project_id')->nullable()->constrained('projects')->onDelete('set null');
            $table->date('start_date');
            $table->date('end_date');
            $table->enum('status', ['Draft', 'Completed'])->default('Draft');
            $table->timestamps();
        });

        // 6. Tabel time_results
        Schema::create('time_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('time_period_id')->constrained('time_periods')->onDelete('cascade');
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            $table->integer('workdays')->default(22);
            $table->integer('present_days')->default(0);
            $table->integer('absent_days')->default(0);
            $table->integer('late_days')->default(0);
            $table->integer('early_departure_days')->default(0);
            $table->integer('leave_days')->default(0);
            $table->decimal('overtime_hours', 8, 2)->default(0.00);
            $table->decimal('deduction_amount', 15, 2)->default(0.00);
            $table->timestamps();

            $table->unique(['time_period_id', 'employee_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('time_results');
        Schema::dropIfExists('time_periods');
        Schema::dropIfExists('time_evaluations');
        Schema::dropIfExists('schedule_assignments');
        Schema::dropIfExists('schedule_groups');
        Schema::dropIfExists('absent_types');
    }
};
