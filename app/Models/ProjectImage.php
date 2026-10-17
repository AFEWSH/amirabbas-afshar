<?php

namespace App\Models;

use App\Models\Concerns\HasPublicTranslations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectImage extends Model
{
    use HasPublicTranslations;

    protected $fillable = ['path', 'alt_text', 'sort_order', 'alt_text_fa'];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
