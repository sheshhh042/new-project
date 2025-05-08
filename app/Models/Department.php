<?php
// app/Models/Department.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $table = 'departments'; // Explicitly set table name
    
    protected $fillable = ['name', 'is_active'];
    
    // If your timestamps columns have different names:
    // const CREATED_AT = 'created_at';
    // const UPDATED_AT = 'updated_at';
    
    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }

}