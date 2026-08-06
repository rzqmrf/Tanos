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
        // 1. Tambah kolom detail proyek ke tabel projects
        Schema::table('projects', function (Blueprint $table) {
            $table->string('project_code')->nullable()->unique()->after('id');
            $table->string('project_name')->nullable()->after('project_code');
            $table->string('customer_name')->nullable()->after('project_name');
            $table->string('contract_number')->nullable()->after('customer_name');
            $table->date('start_date')->nullable()->after('contract_number');
            $table->date('end_date')->nullable()->after('start_date');
            $table->string('cost_center')->nullable()->after('end_date');
            $table->string('fund_center')->nullable()->after('cost_center');
        });

        // 2. Buat tabel rincian item Pranota Billing
        Schema::create('pranota_billing_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pranota_billing_id')->constrained('pranota_billings')->onDelete('cascade');
            $table->foreignId('wbs_element_id')->nullable()->constrained('wbs_elements')->onDelete('set null');
            $table->string('item_name');
            $table->decimal('dpp_amount', 15, 2)->default(0.00);
            $table->decimal('management_fee_rate', 5, 2)->default(10.00);
            $table->decimal('management_fee_amount', 15, 2)->default(0.00);
            $table->decimal('ppn_rate', 5, 2)->default(11.00);
            $table->decimal('ppn_amount', 15, 2)->default(0.00);
            $table->decimal('total_amount', 15, 2)->default(0.00);
            $table->timestamps();
        });

        // 3. Buat tabel rincian item Nota Billing
        Schema::create('nota_billing_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('nota_billing_id')->constrained('nota_billings')->onDelete('cascade');
            $table->foreignId('pranota_billing_id')->nullable()->constrained('pranota_billings')->onDelete('set null');
            $table->string('item_name');
            $table->decimal('dpp_amount', 15, 2)->default(0.00);
            $table->decimal('management_fee_amount', 15, 2)->default(0.00);
            $table->decimal('ppn_amount', 15, 2)->default(0.00);
            $table->decimal('total_amount', 15, 2)->default(0.00);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nota_billing_items');
        Schema::dropIfExists('pranota_billing_items');

        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn([
                'project_code',
                'project_name',
                'customer_name',
                'contract_number',
                'start_date',
                'end_date',
                'cost_center',
                'fund_center',
            ]);
        });
    }
};
