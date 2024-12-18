<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    protected $fillable = [
        'creator_id',
        'title',
        'description',
        'status',
        'level',
        'totalScore',
        'specialized',
        'subject',
        'topic',
        'visibility',
    ];

    public function homeworks()
    {
        return $this->hasMany(Homework::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function submissions()
    {
        return $this->hasMany(Submission::class);
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }
}
