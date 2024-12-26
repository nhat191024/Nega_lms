<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassAnswer extends Model
{
    protected $fillable = [
        'class_submit_id',
        'quiz_id',
        'choice_id',
    ];

    public function submit()
    {
        return $this->belongsTo(ClassSubmit::class);
    }

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    public function choice()
    {
        return $this->belongsTo(Choice::class);
    }
}
