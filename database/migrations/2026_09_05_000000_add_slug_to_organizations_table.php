<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('organizations', 'slug')) {
            Schema::table('organizations', function (Blueprint $table): void {
                $table->string('slug')->nullable()->after('name');
            });
        }

        $organizations = \Illuminate\Support\Facades\DB::table('organizations')
            ->whereNull('slug')
            ->orderBy('id')
            ->get(['id', 'name']);

        foreach ($organizations as $organization) {
            $base = Str::slug($organization->name) ?: 'organization';
            $slug = $base;
            $suffix = 2;

            while (\Illuminate\Support\Facades\DB::table('organizations')
                ->where('slug', $slug)
                ->where('id', '!=', $organization->id)
                ->exists()) {
                $slug = $base . '-' . $suffix++;
            }

            \Illuminate\Support\Facades\DB::table('organizations')
                ->where('id', $organization->id)
                ->update(['slug' => $slug]);
        }

        if (! Schema::hasIndex('organizations', ['slug'])) {
            Schema::table('organizations', function (Blueprint $table): void {
                $table->unique('slug');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('organizations', 'slug')) {
            Schema::table('organizations', function (Blueprint $table): void {
                $table->dropUnique(['slug']);
                $table->dropColumn('slug');
            });
        }
    }

};
