<?php

namespace App\Models;

use App\Models\Concerns\HasPublicTranslations;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BlogPost extends Model
{
    use HasPublicTranslations;

    protected $fillable = ['blog_category_id', 'title', 'slug', 'excerpt', 'content', 'featured_image_path', 'featured_image_alt', 'status', 'published_at', 'seo_title', 'seo_description', 'title_fa', 'excerpt_fa', 'content_fa', 'featured_image_alt_fa', 'seo_title_fa', 'seo_description_fa'];

    protected function casts(): array
    {
        return ['published_at' => 'datetime'];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(BlogCategory::class, 'blog_category_id');
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published')->whereNotNull('published_at')->where('published_at', '<=', now());
    }
}
