<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'network_zone', 'city', 'image'];

    public function servers()
    {
        return $this->hasMany(Server::class); // Assuming you have a Server model
    }
}
