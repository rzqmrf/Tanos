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
        // 1. Profit Centers
        Schema::create('fa_profit_centers', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique();
            $table->string('name');
            $table->string('segment', 100)->nullable();
            $table->string('person_in_charge')->nullable();
            $table->text('description')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        // 2. Cost Centers
        Schema::create('fa_cost_centers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('profit_center_id')->nullable()->constrained('fa_profit_centers')->nullOnDelete();
            $table->string('code', 50)->unique();
            $table->string('name');
            $table->string('department', 100)->nullable();
            $table->string('person_in_charge')->nullable();
            $table->text('description')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        // 3. Fund Centers
        Schema::create('fa_fund_centers', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique();
            $table->string('name');
            $table->decimal('budget_limit', 18, 2)->default(0.00);
            $table->string('currency', 10)->default('IDR');
            $table->text('description')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        // 4. Currencies
        Schema::create('fa_currencies', function (Blueprint $table) {
            $table->id();
            $table->string('code', 10)->unique(); // IDR, USD, EUR, SGD, JPY
            $table->string('name', 50);
            $table->string('symbol', 10)->default('Rp');
            $table->boolean('is_default')->default(false);
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        // 5. Currency Exchange Rates
        Schema::create('fa_currency_rates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('currency_id')->constrained('fa_currencies')->cascadeOnDelete();
            $table->decimal('rate_to_idr', 18, 4)->default(1.0000);
            $table->date('effective_date');
            $table->string('source', 100)->default('Bank Indonesia');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // 6. Company Bank Accounts
        Schema::create('fa_company_bank_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('bank_name', 100);
            $table->string('account_number', 50)->unique();
            $table->string('account_holder', 150);
            $table->string('branch', 100)->nullable();
            $table->string('currency', 10)->default('IDR');
            $table->foreignId('chart_of_account_id')->nullable()->constrained('chart_of_accounts')->nullOnDelete();
            $table->boolean('is_primary')->default(false);
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        // 7. Fiscal Periods
        Schema::create('fa_fiscal_periods', function (Blueprint $table) {
            $table->id();
            $table->integer('year');
            $table->tinyInteger('month'); // 1-12
            $table->string('period_name', 100); // e.g. "Agustus 2026"
            $table->date('start_date');
            $table->date('end_date');
            $table->enum('status', ['Open', 'Closed', 'Special'])->default('Open');
            $table->timestamp('closed_at')->nullable();
            $table->foreignId('closed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->unique(['year', 'month']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fa_fiscal_periods');
        Schema::dropIfExists('fa_company_bank_accounts');
        Schema::dropIfExists('fa_currency_rates');
        Schema::dropIfExists('fa_currencies');
        Schema::dropIfExists('fa_fund_centers');
        Schema::dropIfExists('fa_cost_centers');
        Schema::dropIfExists('fa_profit_centers');
    }
};
