<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassCategory extends Model
{
    protected $fillable = [
        'class_id',
        'category_id',
    ];

    public function classes()
    {
        return $this->belongsToMany(Classes::class, 'class_categories', 'category_id', 'class_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
