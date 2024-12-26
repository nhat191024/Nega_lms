<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Choice extends Model
{
    protected $fillable = [
        'quiz_id',
        'choice',
        'is_correct'
    ];

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    public function classAnswers()
    {
        return $this->hasMany(ClassAnswer::class);
    }
}
