<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    protected $fillable = [
        'question_id',
        'user_id',
        'choice_id',
        'assignment_id',
        'homework_id',
        'link',
    ];

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function choice()
    {
        return $this->belongsTo(Choice::class);
    }

    public function homework()
    {
        return $this->belongsTo(Homework::class);
    }

    public function assignment()
    {
        return $this->belongsTo(Assignment::class);
    }
}
