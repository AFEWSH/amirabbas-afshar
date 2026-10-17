<?php

namespace App\Models;

use App\Models\Concerns\HasPublicTranslations;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class WorkExperience extends Model
{
    use HasPublicTranslations;

    protected $fillable = ['company', 'job_title', 'employment_type', 'location', 'start_date', 'end_date', 'is_current', 'description', 'responsibilities', 'achievements', 'sort_order', 'is_visible', 'company_fa', 'job_title_fa', 'employment_type_fa', 'location_fa', 'description_fa', 'responsibilities_fa', 'achievements_fa'];

    protected function casts(): array
    {
        return ['start_date' => 'date', 'end_date' => 'date', 'is_current' => 'boolean', 'is_visible' => 'boolean'];
    }

    public function scopeVisible(Builder $query): Builder
    {
        return $query->where('is_visible', true);
    }
}
