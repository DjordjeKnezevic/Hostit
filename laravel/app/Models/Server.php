<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Server extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'location_id', 'server_type_id'
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

    public function serverType()
    {
        return $this->belongsTo(ServerType::class);
    }
}
