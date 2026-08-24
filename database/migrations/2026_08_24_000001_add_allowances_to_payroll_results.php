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
        Schema::table('payroll_results', function (Blueprint $table) {
            $table->decimal('allowances', 15, 2)->default(0.00)->after('overtime_pay');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payroll_results', function (Blueprint $table) {
            $table->dropColumn('allowances');
        });
    }
};
