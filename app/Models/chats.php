<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Notifications\CustomVerifyEmail;
use App\Models\User;

class chats extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'application_id',
        'read',
        'message',
        'del',
    ];

    /**
     * Relationship with the User model.
     * A chat belongs to a user.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship with the feriApp model.
     * A chat belongs to an application.
     */
    public function application()
    {
        return $this->belongsTo(feriApp::class, 'application_id');
    }
}