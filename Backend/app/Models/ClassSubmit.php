<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassSubmit extends Model
{
    protected $fillable = ['class_assignment_id'];

    public function assignment()
    {
        return $this->belongsTo(ClassAssignment::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function answers()
    {
        return $this->hasMany(ClassAnswer::class);
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id'); // 'student_id' là cột khóa ngoại trong bảng ClassSubmit trỏ đến bảng User
    }
}
