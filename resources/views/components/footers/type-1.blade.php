<footer class="bg-gray-900 text-white mt-12 py-16 border-t-[8px] border-primary relative overflow-hidden">


    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12">
            
            <!-- Company Info -->
            <div class="lg:col-span-1">
                <a href="{{ route('home') }}" class="text-3xl font-black text-white tracking-tighter uppercase inline-block mb-4">
                    @if(!empty($site['site_logo']))
                        <img class="h-10 w-auto" src="{{ asset('storage/' . $site['site_logo']) }}" alt="{{ $site['site_name'] ?? 'Logo' }}" style="filter: brightness(0) invert(1);">
                    @else
                        {{ $site['site_name'] ?? 'SaaS Haber' }}
                    @endif
                </a>
                <p class="text-gray-400 text-sm leading-relaxed mb-6">
                    {{ $site['site_description'] ?? 'Türkiye\'nin ve dünyanın en güncel, tarafsız ve hızlı haber platformu. Anında haberiniz olsun.' }}
                </p>
                <div class="flex space-x-4">
                    <a href="#" class="w-8 h-8 rounded-full bg-gray-800 flex items-center justify-center text-gray-400 hover:bg-primary hover:text-white transition-all"><x-heroicon-s-globe-alt class="w-4 h-4"/></a>
                    <a href="#" class="w-8 h-8 rounded-full bg-gray-800 flex items-center justify-center text-gray-400 hover:bg-primary hover:text-white transition-all"><x-heroicon-s-hashtag class="w-4 h-4"/></a>
                    <a href="#" class="w-8 h-8 rounded-full bg-gray-800 flex items-center justify-center text-gray-400 hover:bg-primary hover:text-white transition-all"><x-heroicon-s-video-camera class="w-4 h-4"/></a>
                </div>
            </div>

            <!-- Categories -->
            <div>
                <h4 class="text-lg font-bold text-white mb-6 relative inline-block">
                    Kategoriler
                    <span class="absolute -bottom-2 left-0 w-1/2 h-1 bg-primary rounded-full"></span>
                </h4>
                <ul class="space-y-3">
                    @foreach($headerCategories->take(6) as $cat)
                        <li>
                            <a href="{{ route('category', $cat->slug) }}" class="text-gray-400 hover:text-white hover:translate-x-1 inline-block transition-all text-sm font-medium">
                                {{ $cat->name }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            <!-- Quick Links -->
            <div>
                <h4 class="text-lg font-bold text-white mb-6 relative inline-block">
                    Hızlı Erişim
                    <span class="absolute -bottom-2 left-0 w-1/2 h-1 bg-primary rounded-full"></span>
                </h4>
                <ul class="space-y-3 text-sm text-gray-400 font-medium">
                    <li><a href="#" class="hover:text-white hover:translate-x-1 inline-block transition-all">Künye</a></li>
                    <li><a href="#" class="hover:text-white hover:translate-x-1 inline-block transition-all">İletişim</a></li>
                    <li><a href="#" class="hover:text-white hover:translate-x-1 inline-block transition-all">Gizlilik Politikası</a></li>
                    <li><a href="#" class="hover:text-white hover:translate-x-1 inline-block transition-all">Kullanım Koşulları</a></li>
                    <li><a href="#" class="hover:text-white hover:translate-x-1 inline-block transition-all">Çerez Politikası</a></li>
                </ul>
            </div>

            <!-- Newsletter -->
            <div>
                <h4 class="text-lg font-bold text-white mb-6 relative inline-block">
                    Bültene Aboneliği
                    <span class="absolute -bottom-2 left-0 w-1/2 h-1 bg-primary rounded-full"></span>
                </h4>
                <p class="text-gray-400 text-sm leading-relaxed mb-4">
                    Günün en önemli gelişmelerini her sabah e-posta kutunuza gönderelim.
                </p>
                <form class="flex flex-col space-y-2" onsubmit="event.preventDefault(); alert('Kayıt başarılı!');">
                    <input type="email" placeholder="E-posta adresiniz..." class="bg-gray-800 border-none rounded px-4 py-2.5 text-sm text-white focus:ring-2 focus:ring-primary focus:outline-none" required>
                    <button type="submit" class="bg-primary hover:bg-primary-dark transition-colors rounded px-4 py-2.5 text-sm font-bold text-white uppercase tracking-wider">
                        Abone Ol
                    </button>
                </form>
            </div>
            
        </div>
        
        <div class="border-t border-gray-800 mt-12 pt-8 flex flex-col md:flex-row items-center justify-between text-sm text-gray-500 font-medium">
            <p>&copy; {{ date('Y') }} {{ $site['site_name'] ?? 'SaaS Haber' }}. Tüm hakları saklıdır.</p>
            <p class="mt-4 md:mt-0">Altyapı: <a href="https://dogacdovan.com/" target="_blank" class="text-gray-400 hover:text-white font-bold transition-colors">Doğaç Dovan</a></p>
        </div>
    </div>
</footer>
