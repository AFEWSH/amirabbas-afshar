<?php

namespace App\Models;

use App\Models\Concerns\HasPublicTranslations;
use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    use HasPublicTranslations;

    protected $fillable = ['site_name', 'default_seo_description', 'og_image_path', 'site_name_fa', 'default_seo_description_fa'];
}
