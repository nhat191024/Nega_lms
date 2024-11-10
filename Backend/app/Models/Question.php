<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = [
        'assignment_id',
        'title',
        'description',
    ];

    public function assignment()
    {
        return $this->belongsTo(Assignment::class);
    }

    public function choices()
    {
        return $this->hasMany(Choice::class);
    }

    public function answers()
    {
        return $this->hasMany(Answer::class);
    }
}
