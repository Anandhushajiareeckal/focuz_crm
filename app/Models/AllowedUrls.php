<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AllowedUrls extends Model
{
    use HasFactory;

    protected $table_name = 'allowed_urls';
}
