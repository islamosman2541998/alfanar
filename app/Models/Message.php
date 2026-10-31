<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = ['name', 'phone', 'email', 'service_id', 'message', 'is_read', 'locale', 'ip'];

    protected $casts = ['is_read' => 'boolean'];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
