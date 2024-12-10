<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    protected $fillable = [
        'creator_id',
        'name',
        'description',
        'status',
        'level',
        'duration',
        'totalScore',
        'specialized',
        'subject',
        'topic',
        'start_date',
        'due_date',
        'auto_grade',
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
