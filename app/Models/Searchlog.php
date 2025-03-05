<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Searchlog extends Model
{
    use HasFactory;

    protected $fillable = ['keyword'];
}
