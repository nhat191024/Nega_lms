<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssignmentQuiz extends Model
{
    protected $fillable = ['assignment_id', 'quiz_id'];

    public function assignment()
    {
        return $this->belongsTo(ClassAssignment::class);
    }

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }
}
