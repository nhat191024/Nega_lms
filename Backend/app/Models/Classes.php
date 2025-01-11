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
        return $this->belongsToMany(Category::class, 'class_categories', 'class_id', 'category_id');
    }

    public function assignments()
    {
        return $this->hasMany(ClassAssignment::class, 'class_id');
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
