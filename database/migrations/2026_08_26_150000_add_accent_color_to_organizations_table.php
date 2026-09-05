<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Placeholder migration - accent_color and theme settings are handled in 
     * the theme_settings migration (2026_08_26_160000).
     */
    public function up(): void
    {
        // No-op: accent_color is added via theme_settings migration
    }

    public function down(): void
    {
        // No-op
    }
};