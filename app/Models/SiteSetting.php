<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    protected $fillable = ['site_name', 'default_seo_description', 'og_image_path'];
}
