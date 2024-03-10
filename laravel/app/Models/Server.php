<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Server extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'location_id', 'cpu_cores', 'ram', 'storage', 'network_speed'
    ];

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function pricing()
    {
        return $this->morphMany(Pricing::class, 'service');
    }

    public function subscriptions()
    {
        return $this->morphMany(Subscription::class, 'service');
    }
}
