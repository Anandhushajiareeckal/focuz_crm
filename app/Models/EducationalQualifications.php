<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EducationalQualifications extends Model
{
    use HasFactory;
    protected $fillable = [
        'student_id',
        'field_of_study',
        'graduation_year',
        'gpa',
        'other_degree_name',
        'other_college_name',
        'institution_id',
        'degree_id',
        'abc_id',
        'deb_id'
    ];

    public function university()
    {
        return $this->belongsTo(Universities::class, 'institution_id');
    }

    public function degrees()
    {
        return $this->belongsTo(Degrees::class, 'degree_id');
    }
}
