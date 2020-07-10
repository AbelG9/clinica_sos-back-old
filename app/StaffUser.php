<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class StaffUser extends Authenticatable
{
    use Notifiable, HasApiTokens;

    protected $table = 'staffuser';

    protected $hidden = [
        'password',
    ];
}
