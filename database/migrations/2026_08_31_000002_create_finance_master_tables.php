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
        Schema::create('fa_account_groups', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique();
            $table->string('name');
            $table->enum('category', ['balance_sheet', 'income_statement'])->default('balance_sheet');
            $table->text('description')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        Schema::create('chart_of_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('account_group_id')->nullable()->constrained('fa_account_groups')->nullOnDelete();
            $table->foreignId('parent_id')->nullable()->constrained('chart_of_accounts')->nullOnDelete();
            $table->string('code', 50)->unique();
            $table->string('name');
            $table->tinyInteger('level')->default(1);
            $table->enum('normal_balance', ['debit', 'credit'])->default('debit');
            $table->boolean('is_header')->default(false);
            $table->text('description')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        Schema::create('tax_masters', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique();
            $table->string('name');
            $table->decimal('rate_percent', 5, 2)->default(0.00);
            $table->string('tax_type', 50)->default('ppn'); // ppn, pph21, pph23, pph4_2, other
            $table->text('description')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chart_of_accounts');
        Schema::dropIfExists('fa_account_groups');
        Schema::dropIfExists('tax_masters');
    }
};
