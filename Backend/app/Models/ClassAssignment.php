<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassAssignment extends Model
{
    protected $fillable = [
        'class_id',
        'type',
        'title',
        'description',
        'duration',
        'start_date',
        'due_date',
        'status',
    ];

    public function class()
    {
        return $this->belongsTo(Classes::class);
    }

    public function quizzes()
    {
        return $this->hasOne(AssignmentQuiz::class);
    }

    public function submits()
    {
        return $this->hasMany(ClassSubmit::class);
    }
}
