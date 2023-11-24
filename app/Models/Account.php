<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class Account extends Model
{
    use HasFactory, HasUlids;

    protected $fillable = [
        'username',
        'password',
        'user_id'
    ];

    protected $hidden = [
        'user_id'
    ];

    protected $casts = [
        'password' => 'hashed',
    ];

    protected $dateFormat = 'U';
}
