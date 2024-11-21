<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    protected $fillable = [
        'class_id',
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

    public function class()
    {
        return $this->belongsTo(Classes::class);
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
