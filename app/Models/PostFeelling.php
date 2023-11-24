<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class PostFeelling extends Model
{
    use HasFactory, HasUlids;

    protected $fillable = [
        'posts_id',
        'feelling_id',
        'user_id'
    ];

    protected $hidden = [
        'posts_id',
        'feelling_id',
        'user_id'
    ];

    protected $dateFormat = 'U';
}
