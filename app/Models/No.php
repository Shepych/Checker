<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class No extends Model
{
    use HasFactory;

    protected $table = 'no';

    public function title() {
        return $this->hasOne(Question::class, 'question_id', 'id')->get();
    }
}
