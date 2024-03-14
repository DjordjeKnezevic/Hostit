<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NavigationLink extends Model
{
    protected $fillable = ['name', 'route', 'icon', 'is_footer'];
}
