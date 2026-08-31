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
        Schema::create('partner_types', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        Schema::create('partners', function (Blueprint $table) {
            $table->id();
            $table->foreignId('partner_type_id')->nullable()->constrained('partner_types')->nullOnDelete();
            $table->string('code', 50)->unique();
            $table->string('name');
            $table->string('npwp', 50)->nullable();
            $table->string('email')->nullable();
            $table->string('phone', 50)->nullable();
            $table->text('address')->nullable();
            $table->string('pic_name')->nullable();
            $table->string('pic_phone', 50)->nullable();
            $table->string('bank_name')->nullable();
            $table->string('bank_account_number', 100)->nullable();
            $table->string('bank_account_holder')->nullable();
            $table->integer('payment_terms_days')->default(30);
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        Schema::create('partner_bank_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('partner_id')->constrained('partners')->cascadeOnDelete();
            $table->string('bank_name');
            $table->string('account_number', 100);
            $table->string('account_holder');
            $table->string('branch')->nullable();
            $table->boolean('is_primary')->default(false);
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('partner_bank_accounts');
        Schema::dropIfExists('partners');
        Schema::dropIfExists('partner_types');
    }
};
