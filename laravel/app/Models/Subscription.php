<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    protected $dates = ['start_date', 'end_date'];

    protected $fillable = [
        'user_id', 'service_id', 'service_type', 'pricing_id', 'start_date', 'end_date'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pricing()
    {
        return $this->belongsTo(Pricing::class);
    }

    public function service()
    {
        return $this->morphTo(__FUNCTION__, 'service_type', 'service_id');
    }

    public function serverStatus()
    {
        return $this->hasOne(ServerStatus::class);
    }
}
