<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuizPackageCategory extends Model
{
    protected $fillable = [
        'quiz_package_id',
        'category_id',
    ];

    public function quizPackage()
    {
        return $this->belongsTo(QuizPackage::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
