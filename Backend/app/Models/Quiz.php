<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    protected $fillable = [
        'question',
        'quiz_package_id'
    ];

    public function quizPackage()
    {
        return $this->belongsTo(QuizPackage::class);
    }

    public function assignments()
    {
        return $this->belongsToMany(ClassAssignment::class, 'assignment_quizzes');
    }

    public function courseAssignment()
    {
        return $this->hasMany(CourseQuiz::class);
    }

    public function choices()
    {
        return $this->hasMany(Choice::class);
    }
}
