<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    use HasFactory;

    public function question() {
        return $this->hasOne(Question::class, 'id', 'question_id')->first();
    }

    public function diagnosis() {
        return $this->hasOne(Diagnos::class, 'id', 'diagnosis_id')->first();
    }
}
