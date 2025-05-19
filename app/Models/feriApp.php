<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;

class feriApp extends Model
{
    // Explicitly specify the correct table name
    protected $table = 'feriapp';
    
    protected $guarded = [];

    // Define relationship to User if needed
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}