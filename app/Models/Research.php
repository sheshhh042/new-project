<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Research extends Model
{
    use HasFactory, SoftDeletes; // Combine both traits in one line

    protected $fillable = [
        'research_title',
        'author',
        'location',
        'subject_area',
        'date',
        'file_path',
        'department',
        'reference_id',
        'keywords',
    ];

    protected $dates = ['deleted_at']; // Define the deleted_at column for soft deletes

    public function scopeSearchSuggestions($query, $searchTerm)
    {
        return $query->where('Research_Title', 'like', "%{$searchTerm}%")
            ->orWhere('Author', 'like', "%{$searchTerm}%")
            ->orWhere('subject_area', 'like', "%{$searchTerm}%");
    }

}
