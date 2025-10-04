<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payments extends Model
{
    use HasFactory;
    
    
    
    protected $fillable = [
    'student_id', 'course_id', 'amount', 'discount_amount',
    'payment_status', 'verified_by', 'offer_letter_path'
];


    public function payment_methods()
    {
        return $this->belongsTo(PaymentMethods::class, 'payment_method_id');
    }

    public function discounts()
    {
        return $this->belongsTo(Discounts::class, 'promocode');
    }

    public function card_types()
    {
        return $this->belongsTo(CardTypes::class, 'card_type_id');
    }

    public function courses()
    {
        return $this->belongsTo(Courses::class, 'course_id');
    }

    public function course_schedule()
    {
        return $this->belongsTo(CourseSchedules::class, 'course_schedule_id');
    }

    public function student()
    {
        return $this->belongsTo(Students::class, 'student_id');
    }

    public function banks()
    {
        return $this->belongsTo(Banks::class, 'bank_id');
    }

    public function created_user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function installment()
    {
        return $this->hasMany(InstallmentHistory::class, 'payment_id');
    }
}
