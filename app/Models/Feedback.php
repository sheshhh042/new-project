<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory;

    // Define the table name (optional if it matches the plural of the model name)
    protected $table = 'feedback';

    // Define the fillable fields
    protected $fillable = [
        'user_id',
        'rating',
        'feedback',
        'is_read',
    ];

    // Define the relationship with the User model
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}