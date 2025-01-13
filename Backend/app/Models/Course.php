<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = [
        'code',
        'name',
        'description',
        'status',
    ];

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'course_categories');
    }

    public function enrollments()
    {
        return $this->hasMany(CourseEnrollment::class);
    }

    public function assignments()
    {
        return $this->hasMany(CourseAssignment::class);
    }
}
