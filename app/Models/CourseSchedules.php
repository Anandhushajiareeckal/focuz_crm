<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseSchedules extends Model
{
    use HasFactory;

    public function course()
    {
        return $this->belongsTo(Courses::class, 'course_id');
    }

    public function payments()
    {
        return $this->hasMany(Payments::class, 'course_schedule_id');
    }

    public function installment()
    {
        return $this->hasMany(CourseInstallments::class, 'course_schedule_id');
    }
}
