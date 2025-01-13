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
        return $this->belongsTo(Quiz::class, 'quiz_id');
    }

    public function classAnswers()
    {
        return $this->hasMany(ClassAnswer::class);
    }
}
