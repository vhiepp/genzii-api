<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class Storie extends Model
{
    use HasFactory, HasUlids;

    protected $fillable = [
        'content',
        'media_id'
    ];
    
    protected $dateFormat = 'U';
}
