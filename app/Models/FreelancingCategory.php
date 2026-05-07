<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FreelancingCategory extends Model
{
    protected $fillable = ['name', 'group_link', 'is_active', 'sort_order'];
}
