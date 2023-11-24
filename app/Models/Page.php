<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class Page extends Model
{
    use HasFactory, HasUlids;

    protected $fillable = [
        'name',
        'user_author_id',
        'slogan'
    ];

    protected $dateFormat = 'U';
}
