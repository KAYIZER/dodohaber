<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class TenantDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Theme settings
        $settings = [
            ['group' => 'theme', 'key' => 'header_type', 'value' => 'type-1'],
            ['group' => 'theme', 'key' => 'slider_type', 'value' => 'numbered-slider'],
            ['group' => 'theme', 'key' => 'footer_type', 'value' => 'type-1'],
            ['group' => 'theme', 'key' => 'color_primary', 'value' => '#1a56db'],
            ['group' => 'theme', 'key' => 'color_secondary', 'value' => '#7c3aed'],
            ['group' => 'theme', 'key' => 'font_family', 'value' => 'Inter'],

            // General settings
            ['group' => 'general', 'key' => 'site_name', 'value' => 'Haber Sitesi'],
            ['group' => 'general', 'key' => 'site_description', 'value' => 'Güncel haberler ve son dakika gelişmeleri'],
            ['group' => 'general', 'key' => 'site_logo', 'value' => null],
            ['group' => 'general', 'key' => 'site_favicon', 'value' => null],

            // SEO settings
            ['group' => 'seo', 'key' => 'meta_title', 'value' => 'Haber Sitesi - Güncel Haberler'],
            ['group' => 'seo', 'key' => 'meta_description', 'value' => 'En güncel haberler ve son dakika gelişmeleri'],
            ['group' => 'seo', 'key' => 'google_analytics', 'value' => null],
        ];

        foreach ($settings as $setting) {
            Setting::create($setting);
        }
    }
}
