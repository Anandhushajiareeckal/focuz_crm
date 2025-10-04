<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Urls extends Model
{
    use HasFactory;

    // Define the relationship between URLs and users who have access
    public function users()
    {
        return $this->belongsToMany(User::class, 'allowed_urls', 'url_id', 'user_id');
    }
}