<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactRequest extends Model
{
    protected $fillable = [
        'type',
        'status',
        'name',
        'email',
        'phone',
        'company',
        'subject',
        'message',
        'source_url',
        'ip',
        'user_agent',
    ];
}

