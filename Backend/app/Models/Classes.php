<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Classes extends Model
{
    protected $fillable = [
        'code',  
        'name',
        'teacher_id',
        'description',
        'start_date',
        'end_date',
        'status',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
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
        return $this->hasMany(ClassAssignment::class, 'class_id'); // Sử dụng đúng cột 'class_id'
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function notifications()
    {
        return $this->hasMany(ClassNotification::class);
    }

    public function students()
    {
        return $this->belongsToMany(User::class, 'enrollments', 'class_id', 'student_id');
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeUpcoming($query)
    {
        return $query->where('start_date', '>', now());
    }
}
