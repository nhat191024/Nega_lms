<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name', 'parent_id'];

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
        return $this->hasMany(Course::class);
    }
}
