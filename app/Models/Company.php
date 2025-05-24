<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Notifications\CustomVerifyEmail;
use App\Models\User;

class Company extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'type', 'email', 'address'];

    public function users()
    {
        return $this->hasMany(User::class);
    }
}