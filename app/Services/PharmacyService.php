<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class PharmacyService
{
    protected string $apiKey;
    protected string $baseUrl = 'https://api.collectapi.com/health/dutyPharmacy';

    public function __construct()
    {
        $this->apiKey = config('services.collectapi.key', '');
    }

    /**
     * Belirli bir il ve opsiyonel ilçedeki nöbetçi eczaneleri çeker.
     * 
     * @param string $city
     * @param string|null $district
     * @return array
     */
    public function getOnDutyPharmacies(string $city, ?string $district = null): array
    {
        $city = trim($city);
        $district = trim($district ?? '');

        $cacheKey = "pharmacies_" . strtolower($city) . "_" . strtolower($district);

        return Cache::remember($cacheKey, now()->endOfDay(), function () use ($city, $district) {
            try {
                $params = ['il' => $city];
                
                // İlçe belirtilmişse API'ye gönder, yoksa tüm il geneli
                if (!empty($district)) {
                    $params['ilce'] = $district;
                }

                $response = Http::withHeaders([
                    'content-type' => 'application/json',
                    'authorization' => $this->apiKey
                ])->get($this->baseUrl, $params);

                if ($response->successful()) {
                    $data = $response->json();
                    
                    if ($data['success']) {
                        return [
                            'success' => true,
                            'data' => $data['result'] ?? []
                        ];
                    }
                }

                Log::error("CollectAPI Pharmacy Error: " . $response->body());
                
                return [
                    'success' => false,
                    'message' => 'API hatası veya veri bulunamadı.',
                    'data' => []
                ];

            } catch (\Exception $e) {
                Log::error("Pharmacy Service Exception: " . $e->getMessage());
                return [
                    'success' => false,
                    'message' => 'Bağlantı hatası.',
                    'data' => []
                ];
            }
        });
    }
}
