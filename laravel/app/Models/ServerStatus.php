<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServerStatus extends Model
{
    use HasFactory;

    protected $fillable = ['subscription_id', 'status', 'uptime', 'downtime', 'last_started_at', 'last_stopped_at', 'last_crashed_at'];

    protected $dates = ['last_started_at', 'last_stopped_at', 'last_crashed_at'];

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }
}
