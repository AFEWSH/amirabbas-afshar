<?php

namespace App\Models;

use App\Models\Concerns\HasPublicTranslations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProjectCategory extends Model
{
    use HasPublicTranslations;

    protected $fillable = ['name', 'slug', 'name_fa'];

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }
}
