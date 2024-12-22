<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Homework extends Model
{
    protected $fillable = [
        'class_id',
        'type',
        'assignment_id',
        'title',
        'score',
        'description',
        'link',
        'start_datetime',
        'due_datetime',
        'duration',
        'auto_grade',
        'status',
    ];

    public function assignment()
    {
        return $this->belongsTo(Assignment::class);
    }

    public function class()
    {
        return $this->belongsTo(Classes::class);
    }
}
