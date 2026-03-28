<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\Category;
use App\Models\Module;
use App\Services\PharmacyService;
use App\Services\LocationService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PharmacyController extends BaseController
{
    protected PharmacyService $pharmacyService;
    protected LocationService $locationService;

    public function __construct(PharmacyService $pharmacyService, LocationService $locationService)
    {
        parent::__construct();
        
        $this->pharmacyService = $pharmacyService;
        $this->locationService = $locationService;
    }

    public function index($slug)
    {
        // 1. Modül Kontrolü
        $module = Module::where('name', 'pharmacy')->first();
        if (!$module || !$module->is_active) {
            abort(404);
        }

        $defaultSettings = $module->settings ?? [];
        $city = $defaultSettings['city'] ?? 'Manisa';
        $district = $defaultSettings['district'] ?? null;

        // 2. Slug Parçalama
        if ($slug === 'nobetci-eczaneler') {
            // Varsayılan İl/İlçe kalsın
        } else {
            $parts = explode('-', str_replace('-nobetci-eczaneler', '', $slug));
            
            if (count($parts) >= 1) {
                $city = Str::title($parts[0]);
                $district = isset($parts[1]) ? Str::title($parts[1]) : null;
            }
        }

        // 3. Veri Çekme (ilçe opsiyonel — boşsa il geneli döner)
        $pharmacyData = $this->pharmacyService->getOnDutyPharmacies($city, $district);
        
        // Lokasyon Seçici İçin Veriler
        $allCities = $this->locationService->getCities();
        $allDistricts = $this->locationService->getDistricts($city);

        // 4. Dinamik SEO
        $title = ($district ? "{$city} {$district}" : $city) . " Nöbetçi Eczaneler";
        $pageSeo = [
            'title' => $title,
            'description' => "{$city} ili " . ($district ? "{$district} ilçesi " : "") . "güncel nöbetçi eczane listesi, adres ve iletişim bilgileri.",
        ];

        return view('frontend.pharmacy.index', compact('pharmacyData', 'pageSeo', 'city', 'district', 'module', 'slug', 'allCities', 'allDistricts'));
    }
}
