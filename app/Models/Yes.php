<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Yes extends Model
{
    use HasFactory;

    public function title() {
        return $this->hasOne(Question::class, 'question_id', 'id');
    }
}
