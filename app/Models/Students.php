<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Students extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'date_of_birth',
        'phone_number',
        'address',
        'document_verification_status',
    ];

    public static function emailExists($email)
    {
        return DB::table('students')->where('email', $email)->exists();
    }

    public function city()
    {
        return $this->belongsTo(Cities::class, 'city_id');
    }

    public function state()
    {
        return $this->belongsTo(States::class, 'state_id');
    }

    public function country()
    {
        return $this->belongsTo(Countries::class, 'country_id');
    }

    public function identity_card()
    {
        return $this->belongsTo(IdentityCards::class, 'identity_card_id');
    }

    public function religion()
    {
        return $this->belongsTo(Religions::class, 'religion_id');
    }

    public function religion_category()
    {
        return $this->belongsTo(ReligionCategories::class, 'religion_category_id');
    }

    public function employment_status()
    {
        return $this->belongsTo(EmploymentStatuses::class, 'employment_status_id');
    }

    public function marital_status()
    {
        return $this->belongsTo(MaritalStatus::class, 'marital_status_id');
    }

    public function nationality()
    {
        return $this->belongsTo(Countries::class, 'nationality_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employees::class);
    }

    public function payment()
    {
        return $this->hasMany(Payments::class, 'student_id');
    }

    public function course_payment()
    {
        return $this->hasMany(CoursePayments::class, 'student_id'); // Assuming 'branch_id' is the foreign key
    }

    
    public function course_installments()
    {
        return $this->belongsTo(CourseInstallments::class, 'student_id');
    }
}
