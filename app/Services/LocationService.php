<?php

namespace App\Services;

class LocationService
{
    /**
     * @return array
     */
    public function getCities(): array
    {
        return [
            'Adana', 'Adiyaman', 'Afyon', 'Agri', 'Amasya', 'Ankara', 'Antalya', 'Artvin', 
            'Aydin', 'Balikesir', 'Bilecik', 'Bingol', 'Bitlis', 'Bolu', 'Burdur', 'Bursa', 
            'Canakkale', 'Cankiri', 'Corum', 'Denizli', 'Diyarbakir', 'Edirne', 'Elazig', 
            'Erzincan', 'Erzurum', 'Eskisehir', 'Gaziantep', 'Giresun', 'Gumushane', 
            'Hakkari', 'Hatay', 'Isparta', 'Mersin', 'Istanbul', 'Izmir', 'Kars', 
            'Kastamonu', 'Kayseri', 'Kirklareli', 'Kirsehir', 'Kocaeli', 'Konya', 
            'Kutahya', 'Malatya', 'Manisa', 'Kahramanmaras', 'Mardin', 'Mugla', 'Mus', 
            'Nevsehir', 'Nigde', 'Ordu', 'Rize', 'Sakarya', 'Samsun', 'Siirt', 'Sinop', 
            'Sivas', 'Tekirdag', 'Tokat', 'Trabzon', 'Tunceli', 'Sanliurfa', 'Usak', 
            'Van', 'Yozgat', 'Zonguldak', 'Aksaray', 'Bayburt', 'Karaman', 'Kirikkale', 
            'Batman', 'Sirnak', 'Bartin', 'Ardahan', 'Igdir', 'Yalova', 'Karabuk', 
            'Kilis', 'Osmaniye', 'Duzce'
        ];
    }

    /**
     * @param string $city
     * @return array
     */
    public function getDistricts(string $city): array
    {
        $districts = [
            'Manisa' => ['Yunusemre', 'Sehzadeler', 'Kula', 'Salihli', 'Turgutlu', 'Akhisar', 'Soma', 'Alasehir', 'Saruhanli', 'Gordes', 'Demirci', 'Koprubasi', 'Golmarmara', 'Ahmetli', 'Selendi'],
            'Istanbul' => ['Adalar', 'Arnavutkoy', 'Atasehir', 'Avcilar', 'Bagcilar', 'Bahcelievler', 'Bakirkoy', 'Basaksehir', 'Bayrampasa', 'Besiktas', 'Beykoz', 'Beylikduzu', 'Beyoglu', 'Buyukcekmece', 'Catalca', 'Cekmekoy', 'Esenler', 'Esenyurt', 'Eyup', 'Fatih', 'Gaziosmanpasa', 'Gungoren', 'Kadikoy', 'Kagithane', 'Kartal', 'Kucukcekmece', 'Maltepe', 'Pendik', 'Sancaktepe', 'Sariyer', 'Silivri', 'Sultanbeyli', 'Sultangazi', 'Sile', 'Sisli', 'Tuzla', 'Umraniye', 'Uskudar', 'Zeytinburnu'],
            'Izmir' => ['Aliaga', 'Balcova', 'Bayindir', 'Bayrakli', 'Bergama', 'Beydag', 'Bornova', 'Buca', 'Cesme', 'Cigli', 'Dikili', 'Foca', 'Gaziemir', 'Guzelbahce', 'Karabaglar', 'Karaburun', 'Karsiyaka', 'Kemalpasa', 'Kinik', 'Kiraz', 'Konak', 'Menderes', 'Menemen', 'Narlidere', 'Odemis', 'Seferihisar', 'Selcuk', 'Tire', 'Torbali', 'Urla'],
            'Ankara' => ['Akyurt', 'Altindag', 'Ayas', 'Bala', 'Beypazari', 'Camlidere', 'Cankaya', 'Cubuk', 'Elmadag', 'Etimesgut', 'Evren', 'Golbasi', 'Gudul', 'Haymana', 'Kalecik', 'Kazan', 'Kecioren', 'Kizilcahamam', 'Mamak', 'Nallihan', 'Polatli', 'Pursaklar', 'Sincan', 'Sereflikochisar', 'Yenimahalle'],
        ];

        return $districts[$city] ?? ['Merkez'];
    }
}
