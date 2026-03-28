<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Pharmacy modülünü otomatik olarak ekle (Eğer yoksa)
        DB::table('modules')->updateOrInsert(
            ['name' => 'pharmacy'],
            [
                'label' => 'Nöbetçi Eczane',
                'is_active' => false,
                'slug' => 'nobetci-eczaneler',
                'settings' => json_encode([
                    'city' => 'Manisa',
                    'district' => null,
                    'seo_title' => 'Nöbetçi Eczaneler',
                    'seo_description' => 'Güncel nöbetçi eczane listesi.'
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('modules')->where('name', 'pharmacy')->delete();
    }
};
