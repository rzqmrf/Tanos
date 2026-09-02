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
        Schema::table('partner_bank_accounts', function (Blueprint $table) {
            if (!Schema::hasColumn('partner_bank_accounts', 'valid_from')) {
                $table->string('valid_from', 50)->nullable()->default('2025-07-22 00:00:00');
            }
            if (!Schema::hasColumn('partner_bank_accounts', 'valid_to')) {
                $table->string('valid_to', 50)->nullable()->default('9999-12-31 00:00:00');
            }
            if (!Schema::hasColumn('partner_bank_accounts', 'document_status')) {
                $table->string('document_status', 50)->default('Completed');
            }
            if (!Schema::hasColumn('partner_bank_accounts', 'h2h_response_code')) {
                $table->string('h2h_response_code', 100)->default('Sukses');
            }
            if (!Schema::hasColumn('partner_bank_accounts', 'h2h_response_message')) {
                $table->text('h2h_response_message')->nullable();
            }
            if (!Schema::hasColumn('partner_bank_accounts', 'attachment_file')) {
                $table->string('attachment_file')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('partner_bank_accounts', function (Blueprint $table) {
            $table->dropColumn([
                'valid_from',
                'valid_to',
                'document_status',
                'h2h_response_code',
                'h2h_response_message',
                'attachment_file',
            ]);
        });
    }
};
