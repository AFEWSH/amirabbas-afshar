<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $fillable = ['full_name', 'title', 'specialization', 'introduction', 'background', 'expertise', 'interests', 'approach', 'contact_email'];
}
