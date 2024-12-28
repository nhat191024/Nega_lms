<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuizPackage extends Model
{
    protected $fillable = [
        'creator_id',
        'title',
        'description',
        'quiz_id_range',
        'status'
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'quiz_package_categories');
    }

    public function quizzes()
    {
        return $this->hasMany(Quiz::class);
    }
}
