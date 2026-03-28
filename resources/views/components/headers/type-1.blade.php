<header class="bg-white border-b border-gray-200 sticky top-0 z-50 shadow-sm relative">
    
    <!-- Top Bar -->
    <div class="bg-primary text-white text-xs py-1.5 hidden md:block">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center">
            <div class="flex space-x-4">
                <span>{{ \Carbon\Carbon::now()->translatedFormat('d F Y, l') }}</span>
                <a href="#" class="hover:text-gray-200 transition">Künye</a>
                <a href="#" class="hover:text-gray-200 transition">İletişim</a>
            </div>
            <div class="flex space-x-3 items-center">
                <span class="mr-2">Bizi Takip Edin:</span>
                <a href="#" class="hover:text-gray-200"><x-heroicon-s-globe-alt class="w-4 h-4" /></a>
            </div>
        </div>
    </div>

    <!-- Main Header -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center py-4 lg:py-6">
            
            <!-- Mobile Menu Button -->
            <div class="flex items-center md:hidden w-1/4">
                <button @click="mobileMenuOpen = !mobileMenuOpen" class="text-gray-600 hover:text-primary focus:outline-none">
                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path x-show="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        <path x-show="mobileMenuOpen" style="display:none;" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <!-- Logo -->
            <div class="flex-shrink-0 flex items-center justify-center flex-1 md:flex-none w-2/4 md:w-auto">
                <a href="{{ route('home') }}" class="text-3xl font-black text-primary tracking-tighter uppercase">
                    @if(!empty($site['site_logo']))
                        <img class="h-10 w-auto" src="{{ asset('storage/' . $site['site_logo']) }}" alt="{{ $site['site_name'] ?? 'Logo' }}">
                    @else
                        {{ $site['site_name'] ?? 'SaaS Haber' }}
                    @endif
                </a>
            </div>

            <!-- Header Ad/Banner Space (Hidden on Mobile) -->
            <div class="hidden lg:flex flex-1 justify-center px-8">
                <a href="#" class="group relative w-full max-w-[728px] h-[90px] bg-gray-50 border border-dashed border-gray-300 hover:border-primary flex flex-col items-center justify-center text-gray-400 hover:text-primary transition-colors text-sm font-semibold rounded-lg overflow-hidden shadow-inner">
                    <span class="absolute top-1 left-2 text-[10px] text-gray-400 tracking-wider">REKLAM</span>
                    <svg class="w-6 h-6 mb-1 text-gray-300 group-hover:text-primary/70 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/></svg>
                    <span>Sponsorlu Alan (728x90)</span>
                </a>
            </div>

            <!-- Search -->
            <div class="hidden md:flex items-center">
                <div class="relative">
                    <input type="text" placeholder="Haber ara..." class="bg-gray-100 border-transparent rounded-full py-2 pl-4 pr-10 focus:ring-2 focus:ring-primary focus:bg-white text-sm transition-all w-48 lg:w-64">
                    <button class="absolute right-3 top-2.5 text-gray-400 hover:text-primary">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </button>
                </div>
            </div>
            
            <div class="md:hidden flex items-center justify-end w-1/4">
                <button class="text-gray-600">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Navigation Menu (Desktop) -->
    <div class="hidden md:block bg-white shadow-sm relative after:absolute after:bottom-0 after:w-full after:h-0.5 after:bg-primary after:content-['']">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <nav class="flex space-x-1 py-1">
                <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'bg-primary text-white' : 'text-gray-800 hover:text-primary transition-colors' }} px-4 py-3 text-sm font-bold uppercase tracking-wider border-b-2 border-transparent hover:border-primary">
                    Ana Sayfa
                </a>
                @foreach($headerCategories as $cat)
                    <a href="{{ route('category', $cat->slug) }}" class="{{ request()->is('kategori/'.$cat->slug) ? 'bg-primary text-white' : 'text-gray-800 hover:text-primary transition-colors' }} px-4 py-3 text-sm font-bold uppercase tracking-wider border-b-2 border-transparent hover:border-primary">
                        {{ $cat->name }}
                    </a>
                @endforeach
            </nav>
        </div>
    </div>

    <!-- Mobile Menu Container (Alpine.js) -->
    <div x-show="mobileMenuOpen" style="display:none;" x-collapse class="md:hidden bg-white border-t border-gray-200 absolute w-full shadow-lg border-b-4 border-primary">
        <div class="px-2 pt-2 pb-4 space-y-1">
            <a href="{{ route('home') }}" class="block px-4 py-3 text-base font-bold uppercase border-l-4 {{ request()->routeIs('home') ? 'border-primary text-primary bg-primary/10' : 'border-transparent text-gray-800 hover:bg-gray-50' }}">Ana Sayfa</a>
            @foreach($headerCategories as $cat)
                <a href="{{ route('category', $cat->slug) }}" class="block px-4 py-3 text-base font-bold uppercase border-l-4 {{ request()->is('kategori/'.$cat->slug) ? 'border-primary text-primary bg-primary/10' : 'border-transparent text-gray-800 hover:bg-gray-50' }}">
                    {{ $cat->name }}
                </a>
            @endforeach
        </div>
    </div>
</header>
