<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassNotification extends Model
{
    protected $fillable = ['class_id', 'content'];

    public function class()
    {
        return $this->belongsTo(Classes::class);
    }

    public function comments()
    {
        return $this->hasMany(NotificationComment::class);
    }
}
