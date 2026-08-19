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
        Schema::table('employees', function (Blueprint $table) {
            $table->string('place_of_birth')->nullable()->after('religion');
            $table->date('date_of_birth')->nullable()->after('place_of_birth');
            $table->string('gender')->default('Laki-Laki')->after('date_of_birth');
            $table->string('identity_card_number')->nullable()->after('gender');
            $table->string('npwp_number')->nullable()->after('identity_card_number');
            $table->date('valid_from')->nullable()->after('npwp_number');
            $table->date('valid_to')->nullable()->default('9999-12-31')->after('valid_from');
            $table->string('external_id')->nullable()->after('valid_to');
            $table->string('document_status')->default('Completed')->after('external_id');
            $table->string('name_and_title')->nullable()->after('document_status');
            $table->string('suku')->nullable()->after('name_and_title');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn([
                'place_of_birth',
                'date_of_birth',
                'gender',
                'identity_card_number',
                'npwp_number',
                'valid_from',
                'valid_to',
                'external_id',
                'document_status',
                'name_and_title',
                'suku',
            ]);
        });
    }
};
