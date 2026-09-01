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
        Schema::table('users', function (Blueprint $table) {
            $table->string('jabatan')->nullable()->after('username');
            $table->json('role_groups')->nullable()->after('role');
            $table->json('approval_authority')->nullable()->after('role_groups');
            $table->boolean('active')->default(true)->after('approval_authority');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['jabatan', 'role_groups', 'approval_authority', 'active']);
        });
    }
};
