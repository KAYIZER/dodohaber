@if($posts->count() > 0)
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-4">
        
        <!-- DEV MANŞET (Sol - 3 Kolon) -->
        <div class="lg:col-span-3" x-data="{
                activeSlide: 1,
                slides: {{ $posts->count() }},
                timer: null,
                startX: 0,
                
                startLoop() {
                    if(this.timer) clearInterval(this.timer);
                    this.timer = setInterval(() => { 
                        this.next();
                    }, 5000);
                },
                stopLoop() {
                    if(this.timer) clearInterval(this.timer);
                },
                next() {
                    this.activeSlide = this.activeSlide >= this.slides ? 1 : this.activeSlide + 1;
                },
                prev() {
                    this.activeSlide = this.activeSlide <= 1 ? this.slides : this.activeSlide - 1;
                },
                goTo(index) {
                    this.activeSlide = index;
                    this.startLoop();
                },
                handleTouchStart(e) {
                    this.stopLoop();
                    this.startX = e.changedTouches[0].screenX;
                },
                handleTouchEnd(e) {
                    let endX = e.changedTouches[0].screenX;
                    if (this.startX - endX > 40) this.next();
                    if (this.startX - endX < -40) this.prev();
                    this.startLoop();
                }
            }" 
            x-init="startLoop()"
            @mouseenter="stopLoop()" 
            @mouseleave="startLoop()">
            
            <!-- Slider Görsel Alanı (Sol) -->
            <div class="relative w-full aspect-[860/504] rounded-t-xl overflow-hidden shadow-md bg-gray-900 group" 
                 @touchstart="handleTouchStart" 
                 @touchend="handleTouchEnd">
                
                @foreach($posts as $index => $post)
                    <a href="{{ route('post', $post->slug) }}" 
                       x-show="activeSlide === {{ $index + 1 }}"
                       x-transition:enter="transition ease-out duration-500"
                       x-transition:enter-start="opacity-0"
                       x-transition:enter-end="opacity-100"
                       x-transition:leave="transition ease-in duration-300 absolute inset-0"
                       x-transition:leave-start="opacity-100"
                       x-transition:leave-end="opacity-0"
                       class="absolute inset-0 w-full h-full block"
                       {!! $index === 0 ? '' : 'style="display: none;"' !!}>
                        
                        @if($post->image_url)
                            <img src="{{ $post->thumbnail_url }}" width="860" height="504" alt="{{ $post->title }}" class="w-full h-full object-cover select-none pointer-events-none" loading="lazy">
                        @else
                            <div class="w-full h-full bg-gray-200 flex items-center justify-center text-gray-500 font-bold">Görsel Yok</div>
                        @endif
                        
                        <!-- Başlık Overlay -->
                        <div class="absolute bottom-0 left-0 w-full bg-gradient-to-t from-black/90 via-black/50 to-transparent pt-16 pb-4 px-6 opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none md:flex flex-col justify-end hidden">
                            <h2 class="text-white text-xl md:text-2xl font-bold leading-tight line-clamp-2 drop-shadow-lg">{{ $post->title }}</h2>
                        </div>
                    </a>
                @endforeach
                
                <!-- Sağ Sol Okları -->
                <button @click.prevent="prev(); stopLoop(); startLoop();" class="absolute left-3 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-black/40 hover:bg-primary text-white flex items-center justify-center transition-all opacity-0 group-hover:opacity-100 backdrop-blur-sm z-20">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                </button>
                <button @click.prevent="next(); stopLoop(); startLoop();" class="absolute right-3 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-black/40 hover:bg-primary text-white flex items-center justify-center transition-all opacity-0 group-hover:opacity-100 backdrop-blur-sm z-20">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                </button>
            </div>
            
            <!-- Numaratör -->
            <div class="w-full bg-gray-800 text-white border-x border-b border-gray-700 rounded-b-xl overflow-hidden shadow-lg flex flex-wrap">
                @foreach($posts as $index => $post)
                    <button 
                        @click="goTo({{ $index + 1 }})" 
                        class="flex-1 min-w-[32px] sm:min-w-[40px] h-10 sm:h-12 flex items-center justify-center text-xs sm:text-sm font-bold transition-all border-r border-b border-gray-700 hover:bg-primary hover:text-white"
                        :class="activeSlide === {{ $index + 1 }} ? 'bg-primary text-white border-t-2 border-t-white' : 'text-gray-300 border-t-2 border-t-transparent'">
                        {{ $index + 1 }}
                    </button>
                @endforeach
            </div>
        </div>
        
        <!-- DAR MANŞET (Sağ - 1 Kolon) -->
        <div class="lg:col-span-1 mt-6 lg:mt-0 flex flex-col h-full w-full">
            @if(isset($narrowPosts) && $narrowPosts->count() > 0)
                <div class="flex-grow flex flex-col w-full h-full" x-data="{
                        activeSlide: 1,
                        slides: {{ $narrowPosts->take(5)->count() }},
                        timer: null,
                        startX: 0,
                        
                        startLoop() {
                            if(this.timer) clearInterval(this.timer);
                            this.timer = setInterval(() => { this.next(); }, 4000);
                        },
                        stopLoop() {
                            if(this.timer) clearInterval(this.timer);
                        },
                        next() {
                            this.activeSlide = this.activeSlide >= this.slides ? 1 : this.activeSlide + 1;
                        },
                        prev() {
                            this.activeSlide = this.activeSlide <= 1 ? this.slides : this.activeSlide - 1;
                        },
                        goTo(index) {
                            this.activeSlide = index;
                            this.startLoop();
                        },
                        handleTouchStart(e) {
                            this.stopLoop();
                            this.startX = e.changedTouches[0].screenX;
                        },
                        handleTouchEnd(e) {
                            let endX = e.changedTouches[0].screenX;
                            if (this.startX - endX > 40) this.next();
                            if (this.startX - endX < -40) this.prev();
                            this.startLoop();
                        }
                    }" 
                    x-init="startLoop()"
                    @mouseenter="stopLoop()" 
                    @mouseleave="startLoop()">
                    
                    <!-- Slider Görsel Alanı (Sağ) -->
                    <div class="relative w-full aspect-[419/503] lg:aspect-auto lg:flex-grow rounded-t-xl overflow-hidden shadow-md bg-gray-900 group" 
                         @touchstart="handleTouchStart" 
                         @touchend="handleTouchEnd">
                        
                        @foreach($narrowPosts->take(5) as $index => $nPost)
                            <a href="{{ route('post', $nPost->slug) }}" 
                               x-show="activeSlide === {{ $index + 1 }}"
                               x-transition:enter="transition ease-out duration-500"
                               x-transition:enter-start="opacity-0"
                               x-transition:enter-end="opacity-100"
                               x-transition:leave="transition ease-in duration-300 absolute inset-0"
                               x-transition:leave-start="opacity-100"
                               x-transition:leave-end="opacity-0"
                               class="absolute inset-0 w-full h-full block"
                               {!! $index === 0 ? '' : 'style="display: none;"' !!}>
                                
                                @if($nPost->image_url)
                                     <img src="{{ $nPost->thumbnail_url }}" width="419" height="503" alt="{{ $nPost->title }}" class="w-full h-full object-cover select-none pointer-events-none" loading="lazy">
                                @else
                                    <div class="w-full h-full bg-gray-200 flex items-center justify-center text-gray-500 font-bold">Görsel Yok</div>
                                @endif
                                
                                <!-- Arkayı Karartmalı Başlık Alanı -->
                                <div class="absolute bottom-0 left-0 w-full bg-gradient-to-t from-black/90 via-black/70 to-transparent pt-20 pb-4 px-4 pointer-events-none flex flex-col justify-end">
                                    <h2 class="text-white text-base md:text-lg font-bold leading-tight line-clamp-3 drop-shadow-md">{{ $nPost->title }}</h2>
                                </div>
                            </a>
                        @endforeach
                    </div>
                    
                    <!-- Numaratör (Sağ Dar Manşet) -->
                    <div class="w-full bg-gray-800 text-white border-x border-b border-gray-700 rounded-b-xl overflow-hidden shadow-lg flex h-10 sm:h-12 flex-shrink-0">
                        @foreach($narrowPosts->take(5) as $index => $nPost)
                            <button 
                                @click="goTo({{ $index + 1 }})" 
                                class="flex-1 h-full flex items-center justify-center text-xs sm:text-sm font-bold transition-all border-r border-gray-700 last:border-r-0 hover:bg-primary hover:text-white"
                                :class="activeSlide === {{ $index + 1 }} ? 'bg-primary text-white border-t-2 border-t-white' : 'text-gray-300 border-t-2 border-t-transparent'">
                                {{ $index + 1 }}
                            </button>
                        @endforeach
                    </div>
                </div>
            @else
                <!-- Eğer dar manşet yoksa burası boş durmasın diye fallback -->
                <div class="w-full h-full bg-gray-100 rounded-xl border border-gray-200 flex flex-col items-center justify-center text-gray-400 p-6 shadow-sm">
                    <svg class="w-12 h-12 mb-3 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                    <span class="text-sm font-semibold text-center">Dar Manşet Haberleri<br>Burada Listelenir</span>
                </div>
            @endif
        </div>
        
    </div>
</div>
@endif
