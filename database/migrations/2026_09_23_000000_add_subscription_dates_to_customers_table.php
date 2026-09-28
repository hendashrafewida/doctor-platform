<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('customers', function (Blueprint $table): void {
            $table->date('subscription_start_date')->nullable()->after('status');
            $table->date('subscription_end_date')->nullable()->after('subscription_start_date');
        });
    }

    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table): void {
            $table->dropColumn(['subscription_start_date', 'subscription_end_date']);
        });
    }
};
