<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
class Avatar extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'user_id',
        'current'
    ];

    protected $hidden = [
        'user_id'
    ];

    protected $dateFormat = 'U';
}
