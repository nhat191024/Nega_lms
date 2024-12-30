<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassAssignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'class_id', 'type', 'title', 'description', 'duration', 'start_date', 'due_date', 'status'
    ];

    public function quizzes()
    {
        return $this->belongsToMany(Quiz::class, 'assignment_quizzes', 'assignment_id', 'quiz_id');
    }

    public function class()
    {
        return $this->belongsTo(Classes::class, 'class_id'); // Sử dụng đúng cột 'class_id'
    }

    public function submits()
    {
        return $this->hasMany(ClassSubmit::class, 'class_assignment_id');
    }
}
