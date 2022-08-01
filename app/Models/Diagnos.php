<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Diagnos extends Model
{
    use HasFactory;

    protected $table = 'diagnoses';

    public function answers() {
        return $this->hasMany(Answer::class, 'diagnosis_id', 'id')->get();
    }
}
