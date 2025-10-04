<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Streams extends Model
{
    use HasFactory;

    public function educationalqualification()
    {
        return $this->belongsTo(EducationalQualifications::class, 'degree_id');
    }


    public function courseNames()
    {
        return $this->hasMany(CourseNames::class, 'stream_id'); // Assuming a relationship with CourseName
    }

    public function courses()
    {
        return $this->hasMany(Courses::class, 'stream_id'); // Assuming a relationship with CourseName
    }
}
