<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseInstallments extends Model
{
    use HasFactory;


    public function course_schedule()
    {
        return $this->belongsTo(CourseSchedules::class, 'course_schedule_id');
    }

    public function course()
    {
        return $this->belongsTo(Courses::class, 'course_id');
    }

    public function student()
    {
        return $this->belongsTo(Students::class, 'student_id');
    }

    public function installment_hist()
    {
        return $this->hasMany(CourseInstallments::class, 'installment_id');
    }
}
