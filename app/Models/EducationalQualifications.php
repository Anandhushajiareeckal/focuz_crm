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
        'deb_id',
        'sslc_board',
        'sslc_passout',
        'intermediate_board',
        'intermediate_passout'
    ];

    public function university()
    {
        return $this->belongsTo(Universities::class, 'institution_id');
    }

    public function degrees()
    {
        return $this->belongsTo(Degrees::class, 'degree_id');
    }
     public function abc()
    {
        return $this->belongsTo(Abc::class, 'abc_id'); // Replace Abc::class with your actual model
    }

    // Relation for deb_id
    public function deb()
    {
        return $this->belongsTo(Deb::class, 'deb_id'); // Replace Deb::class with your actual model
    }
}
