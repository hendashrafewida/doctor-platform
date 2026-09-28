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
        foreach (['users', 'organizations'] as $tableName) {
            if (! Schema::hasTable($tableName)) {
                continue;
            }

            if (! Schema::hasColumn($tableName, 'sidebar_caption')) {
                Schema::table($tableName, function (Blueprint $table): void {
                    $table->boolean('sidebar_caption')->default(true)->after('accent_color');
                });
            }

            DB::table($tableName)->whereNull('theme_mode')->update(['theme_mode' => 'light']);
            DB::table($tableName)->whereNull('sidebar_theme')->update(['sidebar_theme' => 'light']);
            DB::table($tableName)->whereNull('accent_color')->update(['accent_color' => 'preset-1']);
            DB::table($tableName)->whereNull('sidebar_caption')->update(['sidebar_caption' => true]);

            DB::table($tableName)
                ->whereIn('theme_mode', [null, ''])
                ->update(['theme_mode' => 'light']);

            DB::table($tableName)
                ->whereIn('sidebar_theme', [null, ''])
                ->update(['sidebar_theme' => 'light']);

            DB::table($tableName)
                ->whereIn('accent_color', [null, ''])
                ->update(['accent_color' => 'preset-1']);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        foreach (['users', 'organizations'] as $tableName) {
            if (! Schema::hasTable($tableName) || ! Schema::hasColumn($tableName, 'sidebar_caption')) {
                continue;
            }

            Schema::table($tableName, function (Blueprint $table): void {
                $table->dropColumn('sidebar_caption');
            });
        }
    }
};
