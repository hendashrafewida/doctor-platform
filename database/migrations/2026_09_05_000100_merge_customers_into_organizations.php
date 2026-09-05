<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('organizations', function (Blueprint $table): void {
            if (! Schema::hasColumn('organizations', 'city')) {
                $table->string('city')->nullable()->after('name');
            }

            if (! Schema::hasColumn('organizations', 'specialization_id')) {
                $table->foreignId('specialization_id')->nullable()->after('mobile')->constrained('specializations')->nullOnDelete();
            }
        });

        DB::transaction(function (): void {
            if (Schema::hasColumn('organizations', 'specialization')) {
                DB::table('organizations')
                    ->whereNotNull('specialization')
                    ->orderBy('id')
                    ->get(['id', 'specialization'])
                    ->each(function (object $organization): void {
                        $specializationId = DB::table('specializations')
                            ->where('name', $organization->specialization)
                            ->value('id');

                        if ($specializationId) {
                            DB::table('organizations')
                                ->where('id', $organization->id)
                                ->update(['specialization_id' => $specializationId]);
                        }
                    });
            }

            if (Schema::hasTable('customers')) {
                DB::table('customers')->orderBy('id')->get()->each(function (object $customer): void {
                    $slugBase = Str::slug($customer->name) ?: 'organization';
                    $slug = $slugBase;
                    $suffix = 2;
                    while (DB::table('organizations')->where('slug', $slug)->exists()) {
                        $slug = $slugBase . '-' . $suffix++;
                    }

                    $organizationId = DB::table('organizations')->insertGetId([
                        'name' => $customer->name,
                        'city' => $customer->city,
                        'slug' => $slug,
                        'address' => $customer->address,
                        'mobile' => $customer->mobile,
                        'specialization_id' => $customer->specialization_id,
                        'status' => $customer->status,
                        'password' => Hash::make('change-me'),
                        'created_at' => $customer->created_at,
                        'updated_at' => $customer->updated_at,
                    ]);

                    DB::table('organization_employees')->insert([
                        'organization_id' => $organizationId,
                        'name' => 'admin',
                        'password' => Hash::make('change-me'),
                        'role' => 'admin',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                });
            }
        });

        Schema::table('organizations', function (Blueprint $table): void {
            if (Schema::hasColumn('organizations', 'specialization')) {
                $table->dropColumn('specialization');
            }
        });

        if (Schema::hasTable('customers')) {
            Schema::drop('customers');
        }
    }

    public function down(): void
    {
        throw new RuntimeException('The Customer to Organization merge is irreversible.');
    }
};
