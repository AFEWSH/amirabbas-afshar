<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;

trait HasPublicTranslations
{
    public function publicText(string $field): ?string
    {
        $value = $this->{app()->getLocale() === 'fa' ? $field.'_fa' : $field};

        return filled($value) ? $value : null;
    }

    public function scopeAvailableInPublicLocale(Builder $query, string $field): Builder
    {
        if (app()->getLocale() !== 'fa') {
            return $query;
        }

        return $query->whereNotNull($field.'_fa')->where($field.'_fa', '!=', '');
    }
}
