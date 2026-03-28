<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Post extends Model
{

    protected $fillable = [
        'category_id',
        'user_id',
        'title',
        'slug',
        'spot',
        'content',
        'image_url',
        'status',
        'is_featured',
        'is_narrow_featured',
        'is_breaking',
        'published_at',
        'meta_title',
        'meta_description',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'is_narrow_featured' => 'boolean',
        'is_breaking' => 'boolean',
        'published_at' => 'datetime',
        'view_count' => 'integer',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getThumbnailUrlAttribute(): ?string
    {
        $value = $this->attributes['image_url'] ?? null;

        if (! $value) {
            return null;
        }

        if (filter_var($value, FILTER_VALIDATE_URL)) {
            return $value;
        }

        // AWS SDK / Flysystem'in lokalde DNS/Network blocking (50-60 saniye gecikme) yaşatmaması için
        // R2 URL'sini Storage::disk()->url() yerine manuel ve sıfır gecikmeyle üretiyoruz.
        $baseUrl = config('filesystems.disks.r2.url', env('CLOUDFLARE_R2_URL'));
        $tenantId = tenant('id') ?? config('filesystems.disks.r2.root', '');
        
        $url = rtrim($baseUrl, '/') . '/' . ltrim($tenantId . '/' . $value, '/');
        
        return $url;
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published')
                     ->whereNotNull('published_at')
                     ->where('published_at', '<=', now());
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeNarrowFeatured($query)
    {
        return $query->where('is_narrow_featured', true);
    }

    public function scopeBreaking($query)
    {
        return $query->where('is_breaking', true);
    }

    /**
     * XSS koruması: İçerikten tehlikeli HTML etiketlerini temizle.
     * Blade'de {!! $post->sanitized_content !!} olarak kullanılır.
     */
    public function getSanitizedContentAttribute(): string
    {
        $content = $this->attributes['content'] ?? '';

        // İzin verilen güvenli HTML etiketleri (RichEditor çıktısı için)
        $allowedTags = '<p><br><strong><b><em><i><u><s><ul><ol><li><a><h1><h2><h3><h4><h5><h6>'
            . '<blockquote><pre><code><img><figure><figcaption><table><thead><tbody><tr><th><td>'
            . '<hr><span><div><sub><sup>';

        $cleaned = strip_tags($content, $allowedTags);

        // on* event handler attributelarını temizle (onclick, onerror vb.)
        $cleaned = preg_replace('/\s+on\w+\s*=\s*(["\']).*?\1/i', '', $cleaned);
        $cleaned = preg_replace('/\s+on\w+\s*=\s*[^\s>]+/i', '', $cleaned);

        // javascript: protocol'ünü temizle
        $cleaned = preg_replace('/href\s*=\s*(["\'])\s*javascript:.*?\1/i', 'href=$1#$1', $cleaned);
        $cleaned = preg_replace('/src\s*=\s*(["\'])\s*javascript:.*?\1/i', 'src=$1$1', $cleaned);

        return $cleaned;
    }
}
