<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostedJobs extends Model
{
    protected $fillable = [
        'title',
        'email',
        'description',
        'subcompany',
        'office',
        'department',
        'schedule',
        'recruiting_category',
        'employment_type',
        'years_of_experience',
        'keywords',
        'occupation',
        'occupation_category',
        'status',
        'read',
    ];
}
