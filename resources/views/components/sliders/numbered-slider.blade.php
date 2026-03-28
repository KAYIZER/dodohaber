@if($posts->count() > 0)
    <div x-data="{
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
        @mouseleave="startLoop()"
        class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
        
        <!-- Üst Slider Alanı (Sadece Görsel) -->
        <div class="relative w-full aspect-[21/9] md:aspect-[16/9] lg:aspect-[2/1] rounded-t-xl overflow-hidden shadow-md bg-gray-900" 
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
                   class="absolute inset-0 block w-full h-full"
                   {!! $index === 0 ? '' : 'style="display: none;"' !!}>
                   
                    @if($post->image_url)
                        <!-- Select-none prevents image dragging on desktop so swipe works better -->
                        <img src="{{ $post->thumbnail_url }}" alt="{{ $post->title }}" class="w-full h-full object-cover select-none pointer-events-none">
                    @else
                        <div class="w-full h-full bg-gray-200 flex items-center justify-center text-gray-500 font-bold">Görsel Yok</div>
                    @endif
                </a>
            @endforeach
            
            <!-- Sağ sol ok ikonu -->
            <button @click="prev(); stopLoop(); startLoop();" class="absolute left-2 top-1/2 -translate-y-1/2 w-8 h-8 md:w-10 md:h-10 rounded-full bg-black/40 hover:bg-primary text-white flex items-center justify-center transition opacity-0 group-hover:opacity-100 md:hover:opacity-100 backdrop-blur-sm z-20">
                <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </button>
            <button @click="next(); stopLoop(); startLoop();" class="absolute right-2 top-1/2 -translate-y-1/2 w-8 h-8 md:w-10 md:h-10 rounded-full bg-black/40 hover:bg-primary text-white flex items-center justify-center transition opacity-0 group-hover:opacity-100 md:hover:opacity-100 backdrop-blur-sm z-20">
                <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </button>
        </div>

        <!-- Alt Numaratör Navigasyon Alanı -->
        <!-- Default olarak text-white verdim, Alpine hata verse bile numaralar okunabilir durumda kalır -->
        <div class="w-full flex bg-gray-800 text-white border-x border-b border-gray-700 rounded-b-xl overflow-hidden shadow-lg h-10 sm:h-12">
            @foreach($posts as $index => $post)
                <button 
                    @click="goTo({{ $index + 1 }})" 
                    class="flex-1 h-full flex items-center justify-center text-sm md:text-base font-bold transition-all border-r border-gray-700 last:border-r-0 hover:bg-primary hover:text-white"
                    :class="activeSlide === {{ $index + 1 }} ? 'bg-primary text-white border-t-2 border-t-white' : 'text-gray-300 border-t-2 border-t-transparent'">
                    <span class="md:hidden">{{ $index + 1 }}</span>
                    <span class="hidden md:inline">{{ $index + 1 }}</span>
                </button>
            @endforeach
        </div>
    </div>
@endif
