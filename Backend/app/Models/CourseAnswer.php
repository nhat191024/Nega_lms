<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseAnswer extends Model
{
    protected $fillable = [
        'course_submit_id',
        'quiz_id',
        'answer'
    ];

    public function courseSubmit()
    {
        return $this->belongsTo(CourseSubmit::class);
    }

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    public function choice()
    {
        return $this->belongsTo(Choice::class);
    }
}
