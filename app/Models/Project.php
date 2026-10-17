<?php

namespace App\Models;

use App\Models\Concerns\HasPublicTranslations;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    use HasPublicTranslations;

    protected $fillable = ['project_category_id', 'title', 'slug', 'summary', 'body', 'role', 'challenges', 'solutions', 'github_url', 'live_url', 'is_featured', 'status', 'published_at', 'sort_order', 'seo_title', 'seo_description', 'title_fa', 'summary_fa', 'body_fa', 'role_fa', 'challenges_fa', 'solutions_fa', 'seo_title_fa', 'seo_description_fa'];

    protected function casts(): array
    {
        return ['is_featured' => 'boolean', 'published_at' => 'datetime', 'sort_order' => 'integer'];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ProjectCategory::class, 'project_category_id');
    }

    public function technologies(): BelongsToMany
    {
        return $this->belongsToMany(Technology::class)->withTimestamps();
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProjectImage::class);
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published')->whereNotNull('published_at')->where('published_at', '<=', now());
    }
}
