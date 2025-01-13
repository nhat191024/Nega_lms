<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Certificate extends Model
{
    protected $table = 'certificates';
    protected $fillable = ['student_id', 'class_id', 'course_id'];
    public function student() {
        return $this->belongsTo(User::class,'student_id');
    }
    public function class() {
        return $this->belongsTo(Classes::class, 'class_id');
    }
    public function course() {
        return $this->belongsTo(Course::class, 'course_id');
    }
}
