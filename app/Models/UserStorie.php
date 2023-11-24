<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class UserStorie extends Model
{
    use HasFactory, HasUlids;

    protected $fillable = [
        'user_author_id',
        'story_id'
    ];

    protected $dateFormat = 'U';
}
