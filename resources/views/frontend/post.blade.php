@extends('layouts.app')

@php
/**
 * @var \App\Models\Post $post
 * @var \Illuminate\Database\Eloquent\Collection|\App\Models\Post[] $relatedPosts
 */
@endphp

@section('content')

<article class="bg-white pb-12">
    <!-- Post Header Area -->
    <header class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 pt-12 pb-8">
        
        <!-- Breadcrumb -->
        <nav class="flex mb-6 text-sm font-semibold tracking-wide uppercase text-gray-500">
            <ol class="flex space-x-2 items-center">
                <li><a href="{{ route('home') }}" class="hover:text-primary transition-colors">Ana Sayfa</a></li>
                <li><svg class="w-3 h-3 text-gray-300 mx-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg></li>
                <li><a href="{{ route('category', $post->category?->slug) }}" class="text-primary hover:text-primary-dark transition-colors">{{ $post->category?->name }}</a></li>
            </ol>
        </nav>

        <h1 class="text-4xl md:text-5xl lg:text-5xl font-black text-gray-900 leading-tight md:leading-tight mb-6 tracking-tight">
            {{ $post->title }}
        </h1>
        
        @if($post->spot)
            <p class="text-xl md:text-2xl text-gray-500 mb-8 font-medium leading-relaxed">
                {{ $post->spot }}
            </p>
        @endif

        <div class="flex flex-wrap items-center justify-between text-sm text-gray-500 border-y border-gray-100 py-4 gap-4">
            <div class="flex items-center space-x-4">
                <div class="w-10 h-10 rounded-full bg-primary flex items-center justify-center text-white font-bold text-lg shadow-inner uppercase">
                    {{ mb_substr($post->author?->name ?? '', 0, 1) }}
                </div>
                <div>
                    <div class="font-bold text-gray-900 text-base">{{ $post->author?->name }}</div>
                    <div class="flex items-center mt-0.5">
                        <span class="flex items-center"><x-heroicon-s-clock class="w-3.5 h-3.5 mr-1 text-gray-400"/> {{ $post->published_at?->translatedFormat('d F Y, l - H:i') }}</span>
                    </div>
                </div>
            </div>
            
            <div class="flex items-center space-x-6">
                <span class="flex items-center font-medium bg-gray-50 px-3 py-1.5 rounded text-gray-600">
                    <x-heroicon-s-eye class="w-4 h-4 mr-2 text-gray-400" />
                    {{ number_format($post->view_count, 0, ',', '.') }} Okunma
                </span>
                
                <!-- Share Buttons -->
                <div class="flex items-center space-x-2">
                    <button class="w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center hover:bg-blue-700 transition">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg>
                    </button>
                    <button class="w-8 h-8 rounded-full bg-blue-800 text-white flex items-center justify-center hover:bg-blue-900 transition">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.596 0-5.192 1.583-5.192 4.615v3.385z"/></svg>
                    </button>
                    <button class="w-8 h-8 rounded-full bg-green-500 text-white flex items-center justify-center hover:bg-green-600 transition">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 00-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                    </button>
                </div>
            </div>
        </div>
    </header>

    <!-- Post Image -->
    @if($post->image_url)
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 mb-12">
            <div class="rounded-2xl overflow-hidden shadow-xl relative aspect-[21/9] bg-gray-100">
                <img src="{{ $post->thumbnail_url }}" alt="{{ $post->title }}" class="w-full h-full object-cover">
            </div>
        </div>
    @endif

    <!-- Post Content (Typography) -->
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="prose prose-lg md:prose-xl prose-primary prose-a:text-primary hover:prose-a:text-primary-dark max-w-none text-gray-800 leading-relaxed font-serif prose-img:rounded-xl">
            {!! $post->sanitized_content !!}
        </div>
        
        <!-- Tags / Categories Bottom Indicator -->
        <div class="mt-12 pt-6 border-t border-gray-100 flex items-center">
            <span class="text-sm font-bold text-gray-900 uppercase mr-4">Kategori:</span>
            <a href="{{ route('category', $post->category?->slug) }}" class="inline-block bg-gray-100 hover:bg-primary hover:text-white transition-colors px-4 py-2 rounded-full text-sm font-semibold text-gray-700">
                {{ $post->category?->name }}
            </a>
        </div>
    </div>
</article>

<!-- Related Posts -->
@if($relatedPosts->count() > 0)
<div class="bg-gray-50 border-t border-gray-200 py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-8 pb-2 border-b-2 border-gray-200">
            <h3 class="text-2xl font-black text-gray-900 uppercase tracking-tight relative after:absolute after:-bottom-2.5 after:left-0 after:w-16 after:h-1 after:bg-primary">Bunu Da Okuyun</h3>
        </div>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
            @foreach($relatedPosts as $related)
                <a href="{{ route('post', $related->slug) }}" class="group block bg-white rounded-xl shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden transform hover:-translate-y-1">
                    <div class="aspect-video relative overflow-hidden bg-gray-200">
                        @if($related->image_url)
                             <img src="{{ $related->thumbnail_url }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-gray-200 to-gray-300"></div>
                        @endif
                    </div>
                    <div class="p-5">
                        <span class="text-xs font-bold text-primary uppercase mb-2 block">{{ $related->category?->name }}</span>
                        <h4 class="font-bold text-gray-900 group-hover:text-primary line-clamp-2 text-lg leading-tight">{{ $related->title }}</h4>
                        <div class="mt-4 flex items-center text-xs text-gray-500 font-medium">
                            <span>{{ $related->published_at?->translatedFormat('d M Y') }}</span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</div>
@endif

@endsection
