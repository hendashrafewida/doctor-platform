<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->boolean('is_super_admin')->default(false)->after('password');
        });

        DB::table('users')
            ->whereIn('name', ['جون سميث', 'John Smith'])
            ->orWhere('email', 'like', '%john%')
            ->update(['is_super_admin' => true]);

        DB::table('users')->whereNull('is_super_admin')->update(['is_super_admin' => false]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->dropColumn('is_super_admin');
        });
    }
};
