<?php

namespace App\Models;

use App\Models\Concerns\HasPublicTranslations;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Education extends Model
{
    use HasPublicTranslations;

    protected $table = 'educations';

    protected $fillable = ['institution', 'degree', 'field_of_study', 'start_date', 'end_date', 'description', 'sort_order', 'is_visible', 'institution_fa', 'degree_fa', 'field_of_study_fa', 'description_fa'];

    protected function casts(): array
    {
        return ['start_date' => 'date', 'end_date' => 'date', 'is_visible' => 'boolean'];
    }

    public function scopeVisible(Builder $query): Builder
    {
        return $query->where('is_visible', true);
    }
}
