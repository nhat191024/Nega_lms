<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseQuiz extends Model
{
    protected $fillable = [
        'course_assignment_id',
        'quiz_id'
    ];

    public function courseAssignment()
    {
        return $this->belongsTo(CourseAssignment::class);
    }

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }
}
