<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Courses extends Model
{
    use HasFactory;

    public function university()
    {
        return $this->belongsTo(Universities::class, 'university_id');
    }

    public function payments()
    {
        return $this->hasMany(Courses::class, 'course_id');
    }

    public function coursepayments()
    {
        return $this->hasMany(Courses::class, 'course_id');
    }


   

    public function course_schedules()
    {
        return $this->hasMany(CourseSchedules::class, 'course_id');
    }

    public function streams()
    {
        return $this->belongsTo(Streams::class, 'stream_id');
    }

    public function course_installments()
    {
        return $this->belongsTo(CourseInstallments::class, 'course_id');
    }
}
