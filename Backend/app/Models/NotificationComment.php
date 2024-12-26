<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationComment extends Model
{
    protected $fillable = ['notification_id', 'user_id', 'content'];

    public function notification()
    {
        return $this->belongsTo(ClassNotification::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
