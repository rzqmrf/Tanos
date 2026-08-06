<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Report to database.
     */
    public function up(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->foreignId('project_id')->nullable()->after('segment')->constrained('projects')->onDelete('set null');
            $table->foreignId('job_position_id')->nullable()->after('project_id')->constrained('job_positions')->onDelete('set null');
        });
    }

    /**
     * Reverse migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropForeign(['project_id']);
            $table->dropForeign(['job_position_id']);
            $table->dropColumn(['project_id', 'job_position_id']);
        });
    }
};
