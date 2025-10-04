<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Documents extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'doc_category_id',
        'document_path',
        'status',
        'uploaded_by',
        'verification_screenshot',
        'verification_remarks',
        'verified_by',
        'verified_at',
    ];

    public function doc_category()
    {
        return $this->belongsTo(DocumentCategories::class, 'doc_category_id');
    }
    
    public function student()
{
    return $this->belongsTo(\App\Models\Students::class, 'student_id');
}

public function verifiedBy()
{
    return $this->belongsTo(\App\Models\User::class, 'verified_by');
}

}
