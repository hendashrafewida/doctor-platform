<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $specializations = [
            'باطنة',
            'جراحة عامة',
            'عظام',
            'جلدية',
            'أطفال',
            'نساء وتوليد',
            'أسنان',
            'عيون',
            'أنف وأذن وحنجرة',
            'مسالك بولية',
            'قلب وأوعية دموية',
            'مخ وأعصاب',
            'نفسية',
            'تحاليل طبية',
            'أشعة',
            'طب أسرة',
            'علاج طبيعي',
            'تغذية علاجية',
            'كلى',
            'صدر وحساسية',
            'أورام',
            'أوعية دموية',
            'رمد',
        ];

        DB::table('specializations')->delete();
        DB::table('specializations')->insert(array_map(
            fn (string $name): array => [
                'name' => $name,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            $specializations,
        ));
    }

    public function down(): void
    {
        DB::table('specializations')->delete();
    }
};
