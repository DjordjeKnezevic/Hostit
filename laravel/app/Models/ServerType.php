<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServerType extends Model
{
    protected $fillable = ['name', 'cpu_cores', 'ram', 'storage', 'network_speed'];

    public function servers()
    {
        return $this->hasMany(Server::class);
    }
}
