<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Searchlog extends Model
{
    use HasFactory;

    protected $fillable = ['keyword', 'total']; // Allow mass assignment

    // Optional: Define default order
    public static function latestLogs($limit = 10) {
        return self::latest()->limit($limit)->get();
    
}
}
