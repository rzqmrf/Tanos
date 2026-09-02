<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->string('id_project_humanis')->nullable()->after('project_code');
            $table->text('description')->nullable()->after('project_name');
            $table->string('vendor')->nullable()->after('customer_name');
            $table->string('project_category')->nullable()->default('01. Tenaga Alih Daya Operasional')->after('vendor');
            $table->string('contract_type')->nullable()->default('NON-JOPRO')->after('project_category');
            $table->string('location')->nullable()->after('contract_type');
            $table->string('regional_unit')->nullable()->after('location');
            $table->string('unit_kerja')->nullable()->after('regional_unit');
            $table->date('validity_start')->nullable()->after('end_date');
            $table->date('validity_end')->nullable()->after('validity_start');
        });
    }

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn([
                'id_project_humanis',
                'description',
                'vendor',
                'project_category',
                'contract_type',
                'location',
                'regional_unit',
                'unit_kerja',
                'validity_start',
                'validity_end',
            ]);
        });
    }
};
