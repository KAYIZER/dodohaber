@extends('layouts.app')

@php
/**
 * @var \App\Models\Category $category
 * @var \Illuminate\Pagination\LengthAwarePaginator|\App\Models\Post[] $posts
 */
@endphp

@section('content')

<div class="bg-gradient-to-r from-primary/10 to-secondary/10 py-12 border-b">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-4xl font-extrabold text-gray-900 mb-4">{{ $category->name }}</h1>
        @if($category->description)
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">{{ $category->description }}</p>
        @endif
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        
        <!-- Category Posts List -->
        <div class="md:col-span-2 space-y-8">
            @forelse($posts as $post)
                <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden flex flex-col sm:flex-row hover:shadow-md transition duration-300">
                    <a href="{{ route('post', $post->slug) }}" class="sm:w-1/3 relative bg-gray-200 block shrink-0">
                        @if($post->image_url)
                            <img src="{{ $post->thumbnail_url }}" alt="{{ $post->title }}" class="absolute inset-0 w-full h-full object-cover">
                        @endif
                    </a>
                    <div class="p-6 flex flex-col justify-center flex-grow">
                        <p class="text-xs text-secondary font-bold uppercase mb-2">{{ $post->published_at?->translatedFormat('d F Y') }}</p>
                        <a href="{{ route('post', $post->slug) }}">
                            <h3 class="text-xl font-bold text-gray-900 hover:text-primary transition leading-snug">{{ $post->title }}</h3>
                        </a>
                        <p class="text-gray-600 mt-3 line-clamp-2 text-sm">{{ $post->spot }}</p>
                    </div>
                </div>
            @empty
                <div class="bg-white p-12 text-center rounded-lg border border-gray-200">
                    <h3 class="text-xl font-medium text-gray-500">Bu kategoride henüz haber bulunmuyor.</h3>
                </div>
            @endforelse

            <div class="mt-8">
                {{ $posts->links() }}
            </div>
        </div>

        <!-- Sidebar Placeholder -->
        <div class="space-y-8">
            <div class="bg-gray-50 rounded-lg p-6 border border-gray-100">
                <h3 class="font-bold text-gray-900 border-b pb-3 mb-4">Hakkında</h3>
                <p class="text-gray-600 text-sm">
                    Bu alan reklam, günün yazarı veya yan alan bileşenleri (sidebar components) için ayrılmıştır.
                </p>
            </div>
        </div>

    </div>
</div>

@endsection
