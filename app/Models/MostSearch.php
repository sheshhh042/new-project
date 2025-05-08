<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MostSearch extends Model
{
    protected $table = 'most_searches';
    protected $fillable = ['search_term', 'total_searches'];
}
