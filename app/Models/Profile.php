<?php

namespace App\Models;

use App\Models\Concerns\HasPublicTranslations;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasPublicTranslations;

    protected $fillable = ['full_name', 'title', 'specialization', 'introduction', 'background', 'expertise', 'interests', 'approach', 'contact_email', 'full_name_fa', 'title_fa', 'specialization_fa', 'introduction_fa', 'background_fa', 'expertise_fa', 'interests_fa', 'approach_fa'];
}
