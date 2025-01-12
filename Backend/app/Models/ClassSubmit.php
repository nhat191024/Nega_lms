<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassSubmit extends Model
{
    protected $fillable = ['class_assignment_id', 'student_id', 'answer', 'score'];

    public function assignment()
    {
        return $this->belongsTo(ClassAssignment::class, 'class_assignment_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function answers()
    {
        return $this->hasMany(ClassAnswer::class);
    }
}
