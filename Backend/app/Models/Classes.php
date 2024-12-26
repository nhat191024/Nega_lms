<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Classes extends Model
{
    protected $fillable = [
        'name',
        'teacher_id',
        'category_id',
        'description',
        'start_date',
        'end_date',
        'status',
    ];

    public function teacher()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
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
