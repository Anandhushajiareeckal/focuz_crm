<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Degrees extends Model
{
    use HasFactory;

    public function educationalqualification()
    {
        return $this->belongsTo(EducationalQualifications::class, 'degree_id');
    }
}
