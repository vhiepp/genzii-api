<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class Feelling extends Model
{
    use HasFactory, HasUlids;

    protected $fillable = [
        'name',
        'icon_url'
    ];

    protected $dateFormat = 'U';
}
