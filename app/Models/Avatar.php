<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Avatar extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'url',
        'user_id',
        'current'
    ];

    protected $hidden = [
        'user_id'
    ];

    protected $casts = [
        'created_at' => 'timestamp',
        'updated_at' => 'timestamp'
    ];

    protected $dateFormat = 'U';

    protected $attributes = [
        'url' => '/images/avatars/avatar-default.png',
        'current' => true,
    ];

    public function user():BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}