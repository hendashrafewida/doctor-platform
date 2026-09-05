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
        Schema::table('organizations', function (Blueprint $table): void {
            if (Schema::hasColumn('organizations', 'email')) {
                $table->dropUnique(['email']);
                $table->dropColumn('email');
            }

            if (Schema::hasColumn('organizations', 'remember_token')) {
                $table->dropColumn('remember_token');
            }

            if (! Schema::hasColumn('organizations', 'address')) {
                $table->string('address')->after('name');
            }

            if (! Schema::hasColumn('organizations', 'mobile')) {
                $table->string('mobile')->unique()->after('address');
            }

            if (! Schema::hasColumn('organizations', 'status')) {
                $table->enum('status', ['active', 'suspended'])->default('active')->after('password');
            }

            if (! Schema::hasColumn('organizations', 'specialization')) {
                $table->string('specialization')->after('status');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('organizations', function (Blueprint $table): void {
            // Remove newly added columns
            $table->dropColumn(['address', 'mobile', 'status', 'specialization']);
            
            // Re-add original columns
            $table->string('email')->unique()->after('name');
            $table->rememberToken()->after('password');
        });
    }
};
