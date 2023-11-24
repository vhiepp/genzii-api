<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class PostsHashtag extends Model
{
    use HasFactory, HasUlids;

    protected $fillable = [
        'posts_id',
        'hashtag_id'
    ];

    protected $dateFormat = 'U';
}
