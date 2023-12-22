<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Post extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'description',
        'status',
        'limit',
        'created_at',
        'updated_at'
    ];

    protected $hidden = [
        'description'
    ];

    protected $casts = [
        'created_at' => 'timestamp',
        'updated_at' => 'timestamp'
    ];

    protected $dateFormat = 'U';

    protected $attributes = [
        'limit' => 'all',
        'status' => 'showing'
    ];

    public static function boot(): void
    {
        parent::boot();

        static::retrieved(function ($post) {
            $post->media;
            if (!$post->media) {
                $media = new Media();
                $media->file_url = "https://img.vhiep.com/?w=600&h=800&text=Image&red=240&green=240&blue=240";
                $media->type = "image";
                $post->media = $media;
            }
            $post->caption = $post->description;
            $post->author = $post->authors()->first();
            $heart_total = $post->hearts()->count();
            $comment_total = $post->comments()->count();
            $post->heart = [
                'total' => $heart_total,
                'total_short' => numberhelper()->abbreviateNumber($heart_total)
            ];
            $post->comment = [
                'total' => $comment_total,
                'total_short' => numberhelper()->abbreviateNumber($comment_total)
            ];
            $heart = null;
            if (auth()->check()) {
                $heart = $post->hearts()->where('id', auth()->user()->id)->first();
            }
            $post->is_heart = ($heart && $heart->pivot->active);
        });
    }

    public function media(): BelongsToMany
    {
        return $this->belongsToMany(Media::class, PostMedia::class, 'post_id', 'media_id');
    }

    public function hearts(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'post_hearts', 'post_id', 'user_id', 'id', 'id')->withPivotValue(['active' => true]);
    }

    public function comments(): BelongsToMany
    {
        return $this->belongsToMany(Comment::class, PostComment::class, 'post_id', 'comment_id', 'id', 'id')->withPivotValue(['status' => 'showing']);
    }

    public function authors(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_posts', 'post_id', 'user_author_id');
    }

}
