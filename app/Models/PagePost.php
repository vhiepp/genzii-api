<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class PagePost extends Model
{
    use HasFactory, HasUlids;

    protected $fillable = [
        'page_id',
        'posts_id'
    ];

    protected $hidden = [
        'page_id',
        'posts_id'
    ];

    protected $dateFormat = 'U';
}
