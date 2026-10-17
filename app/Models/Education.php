<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Education extends Model
{
    protected $table = 'educations';

    protected $fillable = ['institution', 'degree', 'field_of_study', 'start_date', 'end_date', 'description', 'sort_order', 'is_visible'];

    protected function casts(): array
    {
        return ['start_date' => 'date', 'end_date' => 'date', 'is_visible' => 'boolean'];
    }

    public function scopeVisible(Builder $query): Builder
    {
        return $query->where('is_visible', true);
    }
}
