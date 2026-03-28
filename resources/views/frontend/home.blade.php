@extends('layouts.app')

@php
/**
 * @var \Illuminate\Database\Eloquent\Collection|\App\Models\Post[] $featuredPosts
 * @var \Illuminate\Database\Eloquent\Collection|\App\Models\Post[] $narrowFeaturedPosts
 * @var \Illuminate\Database\Eloquent\Collection|\App\Models\Post[] $breakingNews
 * @var \Illuminate\Pagination\LengthAwarePaginator|\App\Models\Post[] $latestPosts
 * @var array $theme
 */
@endphp

@section('content')

<!-- Dynamic Header Component -->
@php
    $allowedSliders = ['dual-slider', 'full-width', 'headline-grid', 'numbered-slider'];
    $sliderType = $theme['slider_type'] ?? 'dual-slider';
    if (!in_array($sliderType, $allowedSliders)) $sliderType = 'dual-slider';
@endphp
@includeIf("components.sliders.{$sliderType}", ['posts' => $featuredPosts, 'narrowPosts' => $narrowFeaturedPosts ?? collect()])

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    <!-- Breaking News Bar -->
    @if($breakingNews->count() > 0)
        <div class="bg-white border-l-4 border-red-600 shadow-sm flex items-center mb-10 overflow-hidden rounded-r-md">
            <div class="bg-red-600 text-white font-bold px-4 py-3 uppercase tracking-wider text-sm flex-shrink-0 flex items-center z-10 relative">
                <span class="relative flex h-3 w-3 mr-2">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-white opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-3 w-3 bg-white"></span>
                </span>
                Son Dakika
            </div>
            <div class="overflow-hidden relative w-full h-12 flex items-center bg-red-50 text-red-900">
                <div class="whitespace-nowrap flex pl-4 animate-marquee hover:[animation-play-state:paused]">
                    @foreach($breakingNews as $news)
                        <a href="{{ route('post', $news->slug) }}" class="hover:underline font-semibold text-sm mx-8 flex items-center">
                            <span class="w-1.5 h-1.5 bg-red-600 rounded-full mr-2"></span>
                            {{ $news->title }}
                        </a>
                    @endforeach
                    <!-- Repeat for seamless loop -->
                    @foreach($breakingNews as $news)
                        <a href="{{ route('post', $news->slug) }}" class="hover:underline font-semibold text-sm mx-8 flex items-center">
                            <span class="w-1.5 h-1.5 bg-red-600 rounded-full mr-2"></span>
                            {{ $news->title }}
                        </a>
                    @endforeach
                </div>
            </div>
            <style>
                .animate-marquee { animation: marquee 35s linear infinite; }
                @keyframes marquee { 0% { transform: translateX(0); } 100% { transform: translateX(-50%); } }
            </style>
        </div>
    @endif

    <div class="flex items-center justify-between mb-6 pb-2 border-b-2 border-gray-100">
        <h2 class="text-2xl font-black text-gray-900 uppercase tracking-tight relative after:absolute after:-bottom-2.5 after:left-0 after:w-16 after:h-1 after:bg-primary">Son Haberler</h2>
    </div>

    <!-- Latest Posts Premium Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        @foreach($latestPosts as $index => $post)
            @if($index === 0 || $index === 1)
                <!-- Large Featured Style for first 2 -->
                <div class="lg:col-span-2 bg-white rounded-xl shadow-sm overflow-hidden flex flex-col hover:shadow-md transition group border border-gray-100">
                    <a href="{{ route('post', $post->slug) }}" class="block relative h-64 overflow-hidden">
                        @if($post->image_url)
                            <img src="{{ $post->thumbnail_url }}" alt="{{ $post->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-gray-100 to-gray-200"></div>
                        @endif
                        <div class="absolute bottom-0 left-0 bg-primary text-white text-xs font-bold uppercase tracking-wider px-3 py-1">
                            {{ $post->category?->name }}
                        </div>
                    </a>
                    <div class="p-6 flex flex-col flex-grow">
                        <a href="{{ route('post', $post->slug) }}" class="block mb-3">
                            <h3 class="text-2xl font-bold text-gray-900 group-hover:text-primary transition-colors leading-tight">{{ $post->title }}</h3>
                        </a>
                        <p class="text-gray-600 line-clamp-2 mb-4">{{ $post->spot }}</p>
                        <div class="mt-auto flex items-center justify-between text-xs text-gray-500 font-medium">
                            <span class="flex items-center"><x-heroicon-s-clock class="w-4 h-4 mr-1"/>{{ $post->published_at?->diffForHumans() }}</span>
                            <span class="flex items-center"><x-heroicon-s-eye class="w-4 h-4 mr-1"/>{{ $post->view_count }}</span>
                        </div>
                    </div>
                </div>
            @else
                <!-- Standard Grid Items -->
                <div class="bg-white rounded-xl shadow-sm overflow-hidden flex flex-col hover:shadow-md transition group border border-gray-100">
                    <a href="{{ route('post', $post->slug) }}" class="block relative h-40 overflow-hidden">
                        @if($post->image_url)
                            <img src="{{ $post->thumbnail_url }}" alt="{{ $post->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-gray-100 to-gray-200 animate-pulse"></div>
                        @endif
                    </a>
                    <div class="p-4 flex flex-col flex-grow">
                        <span class="text-primary text-xs font-bold uppercase md:mb-2 block">{{ $post->category?->name }}</span>
                        <a href="{{ route('post', $post->slug) }}" class="block mb-2">
                            <h3 class="text-lg font-bold text-gray-900 group-hover:text-primary transition-colors leading-snug line-clamp-3">{{ $post->title }}</h3>
                        </a>
                        <div class="mt-auto flex items-center text-xs text-gray-500 font-medium pt-3 border-t border-gray-50">
                            <span>{{ $post->published_at?->translatedFormat('d M Y') }}</span>
                        </div>
                    </div>
                </div>
            @endif
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="mt-10">
        {{ $latestPosts->links() }}
    </div>

</div>
@endsection
