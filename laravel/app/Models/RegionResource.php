<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegionResource extends Model
{
    use HasFactory;

    protected $fillable = [
        'location_id', 'total_cpu_cores', 'remaining_cpu_cores',
        'total_ram', 'remaining_ram', 'total_storage', 'remaining_storage',
        'total_bandwidth', 'remaining_bandwidth'
    ];

    public function location()
    {
        return $this->belongsTo(Location::class);
    }
}
