<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    use HasFactory;

    public function diagnoses() {
        return $this->hasMany(Diagnos::class, 'section_id', 'id')->get();
    }
}
