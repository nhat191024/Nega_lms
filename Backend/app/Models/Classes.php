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

    // Quan hệ với giảng viên
    public function teacher()
    {
        return $this->belongsTo(User::class);
    }

    // Quan hệ với danh mục
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'class_categories');
    }

    // Quan hệ với bài tập
    public function assignments()
    {
        return $this->hasMany(ClassAssignment::class);
    }

    // Quan hệ với ghi danh
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    // Quan hệ với thông báo
    public function notifications()
    {
        return $this->hasMany(ClassNotification::class);
    }

    // Quan hệ với học sinh
    public function students()
    {
        return $this->belongsToMany(User::class, 'enrollments', 'class_id', 'student_id');
    }

    // Scope: Lớp học được xuất bản
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    // Scope: Lớp học sắp bắt đầu
    public function scopeUpcoming($query)
    {
        return $query->where('start_date', '>', now());
    }
}
