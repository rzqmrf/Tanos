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
        Schema::table('partners', function (Blueprint $table) {
            $table->string('city')->nullable()->after('address');
            $table->string('identity_card', 50)->nullable()->after('city');
            $table->boolean('is_vendor')->default(true)->after('identity_card');
            $table->boolean('is_customer')->default(true)->after('is_vendor');
            $table->string('chief_name')->nullable()->after('is_customer');
            $table->string('chief_position')->nullable()->after('chief_name');
            $table->boolean('status_hold_dana')->default(false)->after('chief_position');
            $table->boolean('auto_generate_faktur')->default(true)->after('status_hold_dana');
            $table->string('trading_partner')->nullable()->after('auto_generate_faktur');
            $table->string('partner_group')->nullable()->after('trading_partner');
            $table->string('phone_1', 50)->nullable()->after('partner_group');
            $table->string('phone_2', 50)->nullable()->after('phone_1');
            $table->string('ftp_link')->nullable()->after('phone_2');
            $table->string('ftp_port', 20)->nullable()->after('ftp_link');
            $table->string('ftp_user')->nullable()->after('ftp_port');
            $table->string('ftp_pass')->nullable()->after('ftp_user');
            $table->string('kode_mdm', 50)->nullable()->after('ftp_pass');
            $table->text('description')->nullable()->after('kode_mdm');
        });

        Schema::create('partner_business_segments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('partner_id')->constrained('partners')->cascadeOnDelete();
            $table->string('segment_code', 50);
            $table->string('segment_name');
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
        Schema::dropIfExists('partner_business_segments');

        Schema::table('partners', function (Blueprint $table) {
            $table->dropColumn([
                'city',
                'identity_card',
                'is_vendor',
                'is_customer',
                'chief_name',
                'chief_position',
                'status_hold_dana',
                'auto_generate_faktur',
                'trading_partner',
                'partner_group',
                'phone_1',
                'phone_2',
                'ftp_link',
                'ftp_port',
                'ftp_user',
                'ftp_pass',
                'kode_mdm',
                'description',
            ]);
        });
    }
};
