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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            $table->date('date');
            $table->string('status'); // Hadir, Sakit, Izin, Alfa
            $table->time('clock_in')->nullable();
            $table->time('clock_out')->nullable();
            $table->decimal('overtime_hours', 4, 2)->default(0.00);
            $table->text('notes')->nullable();
            $table->timestamps();

            // Unique constraint to prevent duplicate attendance logs for same employee on same day
            $table->unique(['employee_id', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
