<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassCategory extends Model
{
    protected $fillable = [
        'class_id',
        'category_id',
    ];

    public function class()
    {
        return $this->belongsTo(Classes::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
