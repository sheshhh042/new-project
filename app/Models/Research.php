<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Research extends Model
{
    use HasFactory;

    protected $fillable = [
        'research_title', 'author', 'location', 'subject_area', 'date', 'file_path', 'department','reference_id', 'keywords',
    ];

}