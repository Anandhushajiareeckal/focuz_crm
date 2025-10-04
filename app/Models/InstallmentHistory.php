<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstallmentHistory extends Model
{
    use HasFactory;
    protected $table = 'installment_history';

    public function installments()
    {
        return $this->belongsTo(CourseInstallments::class, 'installment_id');
    }

    public function payment()
    {
        return $this->belongsTo(Payments::class, 'payment_id');
    }

}
