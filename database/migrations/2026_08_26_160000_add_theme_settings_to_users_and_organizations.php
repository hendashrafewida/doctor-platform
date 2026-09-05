<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        foreach (['users', 'organizations'] as $tableName) {
            Schema::table($tableName, function (Blueprint $table): void {
                $table->string('theme_mode')->default('light')->after('password');
                $table->string('sidebar_theme')->default('light')->after('theme_mode');
                $table->string('accent_color')->nullable()->after('sidebar_theme');
            });
        }
    }

    public function down(): void
    {
        foreach (['users', 'organizations'] as $tableName) {
            Schema::table($tableName, function (Blueprint $table): void {
                $table->dropColumn(['theme_mode', 'sidebar_theme', 'accent_color']);
            });
        }
    }
};