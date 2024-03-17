<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pricing extends Model
{
    use HasFactory;

    protected $table = 'pricing';

    protected $fillable = [
        'service_id', 'service_type', 'name', 'price', 'period', 'valid_from', 'valid_until'
    ];

    public function service()
    {
        return $this->morphTo(__FUNCTION__, 'service_type', 'service_id');
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }
}
