<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Admin extends User
{
    use HasFactory,Notifiable,HasApiTokens;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone_number',
        'super_admin',
        'status',
    ];
}
