<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class WorkExperience extends Model
{
    protected $fillable = ['company', 'job_title', 'employment_type', 'location', 'start_date', 'end_date', 'is_current', 'description', 'responsibilities', 'achievements', 'sort_order', 'is_visible'];

    protected function casts(): array
    {
        return ['start_date' => 'date', 'end_date' => 'date', 'is_current' => 'boolean', 'is_visible' => 'boolean'];
    }

    public function scopeVisible(Builder $query): Builder
    {
        return $query->where('is_visible', true);
    }
}
