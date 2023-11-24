<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class Comment extends Model
{
    use HasFactory, HasUlids;

    protected $fillable = [
        'content',
        'user_author_id',
        'comment_feedback_id'
    ];

    protected $hidden = [
        'user_author_id',
        'comment_feedback_id'
    ];

    protected $dateFormat = 'U';
}
