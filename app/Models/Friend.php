<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class Friend extends Model
{
    use HasFactory, HasUlids;

    protected $fillable = [
        'user_one_id',
        'user_two_id'
    ];

    protected $hidden = [
        'user_one_id',
        'user_two_id'
    ];

    protected $dateFormat = 'U';
}
