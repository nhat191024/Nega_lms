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

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function quizzes()
    {
        return $this->hasMany(Quiz::class);
    }
}
