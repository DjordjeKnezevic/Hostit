<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    protected $fillable = [
        'name',
        'email',
        'subject',
        'message',
        'response',
        'respondent_id',
    ];

    public function respondent()
    {
        return $this->belongsTo(User::class, 'respondent_id');
    }
}
