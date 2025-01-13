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
        return $this->hasMany(AssignmentQuiz::class, 'assignment_quizzes', 'quiz_id', 'assignment_id');
    }

    public function courseAssignment()
    {
        return $this->hasMany(CourseQuiz::class);
    }

    public function choices()
    {
        return $this->hasMany(Choice::class, 'quiz_id');
    }
}
