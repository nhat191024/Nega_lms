<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseSubmit extends Model
{
    protected $fillable = [
        'course_assignment_id',
        'student_id',
        'score'
    ];

    public function courseAssignment()
    {
        return $this->belongsTo(CourseAssignment::class);
    }

    public function student()
    {
        return $this->belongsTo(User::class);
    }

    public function courseAnswers()
    {
        return $this->hasMany(CourseAnswer::class);
    }
}
