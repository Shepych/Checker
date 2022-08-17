<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subsection extends Model
{
    use HasFactory;

    public function diagnoses() {
        return $this->hasMany(Diagnos::class, 'subsection_id', 'id')->get();
    }
}
