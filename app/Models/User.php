<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    const ROLE_USER = 'user';
    const ROLE_ADMIN = 'admin';
    const ROLE_ADMIN_MASTER = 'admin_master';
    const ROLE_PENDING = 'pending';

    protected $fillable = [
        'email',
        'password',
        'username',
        'role',
        'approved',
    ];

    public function isAdmin()
    {
        return $this->role === self::ROLE_ADMIN || $this->role === self::ROLE_ADMIN_MASTER;
    }

    public function isAdminMaster()
    {
        return $this->role === self::ROLE_ADMIN_MASTER;
    }

    public function isUser()
    {
        return $this->role === self::ROLE_USER;
    }
}
