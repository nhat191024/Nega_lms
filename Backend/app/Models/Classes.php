<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Classes extends Model
{
    protected $fillable = [
        'name',
        'teacher_id',
        'description',
        'start_date',
        'end_date',
        'status',
    ];

    public function teacher()
    {
        return $this->belongsTo(User::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'class_categories');
    }

    public function assignments()
    {
        return $this->hasMany(ClassAssignment::class);
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function notifications()
    {
        return $this->hasMany(ClassNotification::class);
    }
}
