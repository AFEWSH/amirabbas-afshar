<?php

namespace App\Models;

use App\Models\Concerns\HasPublicTranslations;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class SocialLink extends Model
{
    use HasPublicTranslations;

    protected $fillable = ['label', 'url', 'icon_key', 'sort_order', 'is_visible', 'label_fa'];

    protected function casts(): array
    {
        return ['is_visible' => 'boolean', 'sort_order' => 'integer'];
    }

    public function scopeVisible(Builder $query): Builder
    {
        return $query->where('is_visible', true);
    }
}
