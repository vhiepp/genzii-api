<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class UserPost extends Model
{
    use HasFactory, HasUlids;

    protected $fillable = [
        'user_author_id',
        'posts_id'
    ];

    protected $dateFormat = 'U';
}
