<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostFeeling extends Model
{
    use HasFactory;

    protected $fillable = [
        'post_id',
        'feeling_id',
        'user_id'
    ];

    protected $casts = [
        'created_at' => 'timestamp',
        'updated_at' => 'timestamp'
    ];
    protected $hidden = [
        'posts_id',
        'feeling_id',
        'user_id'
    ];

    protected $dateFormat = 'U';
}
