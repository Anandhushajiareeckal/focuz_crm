<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Universities extends Model
{
    use HasFactory;

    
    protected $table = 'universities';

    protected $fillable = [
        'country_id',
        'name',
        'university_code',
        'status'
    ];

    public function educationalqualification()
    {
        return $this->hasMany(EducationalQualifications::class, 'institution_id');
    }

    public function course()
    {
        return $this->hasMany(Courses::class, 'university_id');
    }
}
