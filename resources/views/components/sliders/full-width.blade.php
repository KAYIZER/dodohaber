@if($posts->count() > 0)
    <div x-data="{
        activeSlide: 1,
        slides: {{ $posts->count() }},
        loop() {
            setInterval(() => { this.activeSlide = this.activeSlide === this.slides ? 1 : this.activeSlide + 1 }, 5000);
        }
    }" x-init="loop()" class="w-full relative h-[60vh] md:h-[70vh] overflow-hidden group bg-gray-900">
        
        @foreach($posts as $index => $post)
            <div x-show="activeSlide === {{ $index + 1 }}"
                 x-transition:enter="transition ease-out duration-700"
                 x-transition:enter-start="opacity-0 scale-105"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-500 absolute inset-0"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="absolute inset-0 w-full h-full" style="display: none;">
                 
                <!-- Background Image -->
                @if($post->image_url)
                    <div class="absolute inset-0 w-full h-full">
                        <img src="{{ $post->thumbnail_url }}" class="w-full h-full object-cover opacity-60" alt="{{ $post->title }}">
                        <div class="absolute inset-0 bg-gradient-to-t from-gray-900 via-gray-900/60 to-transparent"></div>
                    </div>
                @else
                    <div class="absolute inset-0 bg-gradient-to-br from-primary to-secondary opacity-90"></div>
                @endif

                <!-- Content -->
                <div class="absolute inset-0 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col justify-end pb-12 md:pb-24">
                    <div class="max-w-4xl">
                        <span class="bg-primary text-white text-xs font-bold uppercase tracking-widest px-3 py-1 rounded inline-block mb-4 shadow-sm">
                            {{ $post->category->name }}
                        </span>
                        <a href="{{ route('post', $post->slug) }}" class="block group-hover:opacity-100">
                            <h1 class="text-3xl md:text-5xl lg:text-5xl font-black text-white mb-4 leading-tight hover:underline drop-shadow-md">
                                {{ $post->title }}
                            </h1>
                        </a>
                        <p class="text-base md:text-xl text-gray-200 line-clamp-2 md:line-clamp-3 font-medium drop-shadow">{{ $post->spot }}</p>
                        
                        <div class="mt-6 flex items-center text-sm font-medium text-gray-300 space-x-4">
                            <span class="flex items-center"><x-heroicon-s-user class="w-4 h-4 mr-1"/> {{ $post->author->name }}</span>
                            <span class="flex items-center"><x-heroicon-s-clock class="w-4 h-4 mr-1"/> {{ $post->published_at?->translatedFormat('d M Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

        <!-- Navigation Arrows -->
        <button @click="activeSlide = activeSlide === 1 ? slides : activeSlide - 1" class="absolute left-4 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-white/10 hover:bg-primary text-white flex items-center justify-center transition opacity-0 md:group-hover:opacity-100 backdrop-blur-sm z-20">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </button>
        <button @click="activeSlide = activeSlide === slides ? 1 : activeSlide + 1" class="absolute right-4 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-white/10 hover:bg-primary text-white flex items-center justify-center transition opacity-0 md:group-hover:opacity-100 backdrop-blur-sm z-20">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </button>

        <!-- Indicators -->
        <div class="absolute bottom-6 left-1/2 -translate-x-1/2 flex space-x-2 z-20">
            @foreach($posts as $index => $post)
                <button @click="activeSlide = {{ $index + 1 }}" 
                        :class="{'w-8 bg-primary': activeSlide === {{ $index + 1 }}, 'w-2 bg-white/50 hover:bg-white': activeSlide !== {{ $index + 1 }}}" 
                        class="h-2 rounded-full transition-all duration-300"></button>
            @endforeach
        </div>
    </div>
@endif
