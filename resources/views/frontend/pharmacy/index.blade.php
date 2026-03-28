@extends('layouts.app')

@php
/**
 * @var array $pageSeo
 * @var array $pharmacyData
 * @var string $city
 * @var string|null $district
 * @var array $allCities
 * @var array $allDistricts
 */
@endphp

@section('content')
<div class="bg-gray-900 pt-16 pb-24 border-b border-gray-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-4xl md:text-5xl font-black text-white mb-6 uppercase tracking-tight">{{ $pageSeo['title'] }}</h1>
        <p class="text-lg text-gray-400 max-w-2xl mx-auto mb-10">
            İl ve ilçe seçerek güncel nöbetçi eczanelere ulaşın.
        </p>

        <!-- Lokasyon Seçici -->
        <div class="bg-white/5 p-6 rounded-2xl border border-white/10 max-w-4xl mx-auto backdrop-blur-sm">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                <div class="text-left">
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2 ml-1">İl Seçin</label>
                    <select id="citySelect" class="w-full bg-gray-800 border-gray-700 text-white rounded-xl py-3 px-4 focus:ring-primary focus:border-primary transition-all">
                        @foreach($allCities as $cityName)
                            <option value="{{ $cityName }}" {{ $city == $cityName ? 'selected' : '' }}>{{ $cityName }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="text-left">
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2 ml-1">İlçe Seçin</label>
                    <select id="districtSelect" class="w-full bg-gray-800 border-gray-700 text-white rounded-xl py-3 px-4 focus:ring-primary focus:border-primary transition-all">
                        <option value="" {{ !$district ? 'selected' : '' }}>Tümü (İl Geneli)</option>
                        @foreach($allDistricts as $distName)
                            <option value="{{ $distName }}" {{ $district == $distName ? 'selected' : '' }}>{{ $distName }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <button id="searchBtn" class="w-full bg-primary hover:bg-primary/90 text-white font-black py-3 px-6 rounded-xl transition-all shadow-lg shadow-primary/20 uppercase tracking-widest text-sm">
                        ECZANE BUL
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="mb-12 flex items-center justify-between border-b border-gray-100 pb-8">
        <h2 class="text-2xl font-bold text-gray-900">{{ $city }} {{ $district ? ' / ' . $district : '' }} Nöbetçi Eczaneleri</h2>
        <div class="text-sm text-gray-400">Toplam {{ is_array($pharmacyData['data'] ?? null) ? count($pharmacyData['data']) : 0 }} sonuç</div>
    </div>

    @if(!$pharmacyData['success'] || empty($pharmacyData['data']))
        <div class="bg-red-50 text-red-700 p-12 rounded-2xl text-center border border-red-100">
            <h3 class="text-xl font-bold mb-2">Bilgi Alınamadı</h3>
            <p>{{ $pharmacyData['message'] ?? 'Bu bölge için aktif nöbetçi verisine ulaşılamadı. Lütfen farklı veya daha genel bir bölge deneyin.' }}</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($pharmacyData['data'] as $pharmacy)
                <div class="group bg-white rounded-2xl border border-gray-100 p-8 hover:border-primary/30 hover:shadow-xl transition-all duration-300">
                    <div class="inline-flex items-center px-3 py-1 rounded bg-red-50 text-red-600 text-[10px] font-black uppercase tracking-widest mb-4">
                        {{ $pharmacy['dist'] ?? ($district ?: $city) }}
                    </div>
                    <h3 class="text-2xl font-black text-gray-900 mb-6 group-hover:text-primary transition-colors">{{ $pharmacy['name'] }}</h3>
                    
                    <div class="space-y-4 mb-8">
                        <p class="text-sm text-gray-600 leading-relaxed">{{ $pharmacy['address'] }}</p>
                        <p class="text-lg font-bold text-gray-900 underline decoration-primary/30">{{ $pharmacy['phone'] }}</p>
                    </div>

                    <a href="https://maps.google.com/?q={{ urlencode($pharmacy['name'] . ' Eczanesi ' . $pharmacy['address']) }}" target="_blank" class="flex items-center justify-center w-full py-4 bg-gray-900 text-white rounded-xl font-bold hover:bg-primary transition-all duration-300 text-sm">
                        ADRESE GİT (HARİTA)
                    </a>
                </div>
            @endforeach
        </div>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const citySelect = document.getElementById('citySelect');
    const districtSelect = document.getElementById('districtSelect');
    const searchBtn = document.getElementById('searchBtn');

    function slugify(text) {
        return text.toString().toLowerCase()
            .replace(/\s+/g, '-')
            .replace(/[ğ]/g, 'g')
            .replace(/[ü]/g, 'u')
            .replace(/[ş]/g, 's')
            .replace(/[ı]/g, 'i')
            .replace(/[ö]/g, 'o')
            .replace(/[ç]/g, 'c')
            .replace(/[^\w\-]+/g, '')
            .replace(/\-\-+/g, '-')
            .replace(/^-+/, '')
            .replace(/-+$/, '');
    }

    function generateSlug(city, district) {
        let slug = slugify(city);
        if (district && district !== '') {
            slug += '-' + slugify(district);
        }
        return slug + '-nobetci-eczaneler';
    }

    searchBtn.addEventListener('click', function() {
        const city = citySelect.value;
        const district = districtSelect.value;
        
        if (!city) {
            alert('Lütfen bir şehir seçiniz.');
            return;
        }

        window.location.href = '/' + generateSlug(city, district);
    });

    // İl değişince sadece il geneli sayfasına yönlendir (ilçe listesi güncellensin)
    citySelect.addEventListener('change', function() {
        window.location.href = '/' + generateSlug(this.value, '');
    });
});
</script>
@endsection
