@if($posts->count() > 0)
    @php
        // İlk haberi büyük manşet, diğer 4 haberi yan manşet olarak ayırıyoruz.
        $mainPost = $posts->first();
        $sidePosts = $posts->slice(1, 4);
    @endphp

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6 pb-2">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-4 md:gap-6 lg:h-[500px]">
            
            <!-- Ana Manşet (Sol Taraf - 2 Kolon veya 3 Kolon) -->
            <div class="lg:col-span-2 xl:col-span-2 relative rounded-2xl overflow-hidden group shadow-md hover:shadow-xl transition-all duration-300 h-[400px] lg:h-full">
                <a href="{{ route('post', $mainPost->slug) }}" class="block w-full h-full">
                    @if($mainPost->image_url)
                        <img src="{{ $mainPost->thumbnail_url }}" alt="{{ $mainPost->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700 ease-out">
                    @else
                        <div class="w-full h-full bg-gradient-to-br from-primary to-secondary"></div>
                    @endif
                    
                    <!-- Gradient Overlay -->
                    <div class="absolute inset-x-0 bottom-0 h-3/4 bg-gradient-to-t from-gray-900 via-gray-900/60 to-transparent"></div>
                    
                    <!-- Content -->
                    <div class="absolute inset-x-0 bottom-0 p-6 md:p-8 flex flex-col justify-end">
                        <span class="bg-primary text-white text-xs font-bold uppercase tracking-widest px-3 py-1 rounded w-max mb-3 md:mb-4 shadow-sm">
                            {{ $mainPost->category->name }}
                        </span>
                        <h2 class="text-2xl md:text-3xl lg:text-4xl font-black text-white leading-tight md:leading-tight mb-2 md:mb-3 drop-shadow-md group-hover:underline decoration-primary decoration-4 underline-offset-4">
                            {{ $mainPost->title }}
                        </h2>
                        @if($mainPost->spot)
                            <p class="text-gray-200 text-sm md:text-base line-clamp-2 drop-shadow hidden md:block">
                                {{ $mainPost->spot }}
                            </p>
                        @endif
                        <div class="mt-4 flex items-center text-xs text-gray-300 font-medium">
                            <span class="flex items-center mr-4"><x-heroicon-s-user class="w-3.5 h-3.5 mr-1"/>{{ $mainPost->author->name }}</span>
                            <span class="flex items-center"><x-heroicon-s-clock class="w-3.5 h-3.5 mr-1"/>{{ $mainPost->published_at?->diffForHumans() }}</span>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Yan Manşetler (Sağ Taraf - Grid) -->
            @if($sidePosts->count() > 0)
                <div class="lg:col-span-2 xl:col-span-2 grid grid-cols-1 sm:grid-cols-2 gap-4 md:gap-6 h-full">
                    @foreach($sidePosts as $post)
                        <div class="relative rounded-xl overflow-hidden group shadow-sm hover:shadow-md transition-all duration-300 h-[200px] lg:h-[238px]">
                            <a href="{{ route('post', $post->slug) }}" class="block w-full h-full">
                                @if($post->image_url)
                                    <img src="{{ $post->thumbnail_url }}" alt="{{ $post->title }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700 ease-out">
                                @else
                                    <div class="w-full h-full bg-gray-200"></div>
                                @endif
                                
                                <!-- Gradient Overlay -->
                                <div class="absolute inset-x-0 bottom-0 h-full bg-gradient-to-t from-gray-900/90 via-gray-900/40 to-transparent opacity-90 group-hover:opacity-100 transition-opacity"></div>
                                
                                <!-- Content -->
                                <div class="absolute inset-x-0 bottom-0 p-4 md:p-5 flex flex-col justify-end">
                                    <span class="text-primary text-[10px] font-bold uppercase tracking-wider mb-1.5 drop-shadow-sm">
                                        {{ $post->category->name }}
                                    </span>
                                    <h3 class="text-sm md:text-base font-bold text-white leading-snug line-clamp-3 drop-shadow-md group-hover:text-primary-100 transition-colors">
                                        {{ $post->title }}
                                    </h3>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            @endif

        </div>
    </div>
@endif
