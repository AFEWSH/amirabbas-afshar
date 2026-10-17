<?php

namespace App\Models;

use App\Models\Concerns\HasPublicTranslations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BlogCategory extends Model
{
    use HasPublicTranslations;

    protected $fillable = ['name', 'slug', 'name_fa'];

    public function posts(): HasMany
    {
        return $this->hasMany(BlogPost::class);
    }
}
