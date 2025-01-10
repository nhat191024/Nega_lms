<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name', 'parent_id', 'status'];

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function quizzes()
    {
        return $this->hasMany(Quiz::class);
    }

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'course_categories');
    }

    public function classes()
    {
        return $this->belongsToMany(Classes::class, 'class_categories');
    }
    public function isChild()
    {
    return $this->parent_id !== null;
    }

}
