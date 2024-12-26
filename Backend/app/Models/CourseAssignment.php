<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseAssignment extends Model
{
    protected $fillable = [
        'course_id',
        'video_url',
        'title',
        'description',
        'duration'
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function courseQuizzes()
    {
        return $this->hasMany(CourseQuiz::class);
    }

    public function courseSubmits()
    {
        return $this->hasMany(CourseSubmit::class);
    }
}
