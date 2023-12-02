<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Support\Str;
use Hamcrest\Core\IsTypeOf;
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasUuids, Sluggable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'full_name',
        'uid',
        'firstname',
        'lastname',
        'email',
        'date_of_birth',
        'gender',
        'address',
        'role'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date_of_birth' => 'timestamp',
        'created_at' => 'timestamp',
        'updated_at' => 'timestamp'
    ];

    protected $dates = [
        'date_of_birth',
    ];

    protected $dateFormat = 'U';

    public function accounts(): HasMany
    {
        return $this->hasMany(Account::class, 'user_id', 'id');
    }

//    public function friends(): BelongsToMany
//    {
//        return $this->belongsToMany(User::class, 'friends', 'user_one_id', 'user_two_id');
//    }

    /**
     * This section manages outgoing friend requests initiated
     * by the current user. These requests are invitations sent
     * to other users, expressing a desire to connect and become
     * friends. The status of these requests may be pending until
     * accepted or rejected by the recipient.
     */
    public function sendFriendRequests(): BelongsToMany
    {
        return $this->belongsToMany(User::class, FriendRequest::class, 'user_request_id', 'user_is_requested_id')->withPivotValue(['status' => 'await']);
    }

    /**
     * This section retrieves and manages incoming friend requests
     * for the current user. Friend requests are invitations sent
     * by other users who wish to connect with the logged-in user.
     * These requests may be accepted or rejected.
     */
    public function friendRequests(): BelongsToMany
    {
        return $this->belongsToMany(User::class, FriendRequest::class, 'user_is_requested_id', 'user_request_id')->withPivotValue(['status' => 'await']);
    }

    /**
     * Retrieves the list of friends for the current user.
     * This function fetches the users who have a confirmed
     * friendship status with the currently logged-in user.
     * It retrieves and returns a collection or an array of
     * user objects representing the friends.
     */
    public function friends(): BelongsToMany
    {
        return $this->belongsToMany(User::class, UserFriend::class, 'user_one_id', 'user_two_id', 'id', 'id', 'hihi')->withPivotValue(['status' => 'friend']);
    }

    /**
     * Add another user as a friend for the current user.
     *
     * @param string $id The ID of the user to be added as a friend, or User $user The user to be added as a friend
     * @return bool Returns true if the friend was added successfully, otherwise returns false if they are already friends
     */
    public function addFriend(string|User $user = null): bool
    {
        if (gettype($user) == 'string') {
            $user = User::find($user);
        }
        if ($user && ($this->id != $user->id)) {
            $this->friends()->syncWithoutDetaching([$user->id => ['status' => 'friend']]);
            $user->friends()->syncWithoutDetaching([$this->id => ['status' => 'friend']]);
            return true;
        }
        return false;
    }

    /**
     * Defines the configuration for generating a slug ('uid') based on the 'slugName'.
     * The method specifies a custom logic to create the slug by replacing non-alphabetic
     * characters with the specified separator, converting the string to lowercase,
     * and adding an underscore prefix if the resulting slug's length is less than or equal to 8.
     * This configuration is used in the sluggable behavior to generate unique slugs
     * for the associated model.
     */
    public function getSlugNameAttribute(): string
    {
        $slugName = str($this->firstname)->slug(' ');
        $name = Str::wordCount($slugName) > 1 ? $slugName: str($this->full_name)->slug(' ');
        return ' ' . str($name)->slug('');
    }
    public function sluggable(): array
    {
        return [
            'uid' => [
                'source' => 'slugName',
                'separator' => '',
                'method' => static function(string $string, string $separator): string {
                    $slug = strtolower(preg_replace('/[^a-z]+/i', $separator, $string));
                    if (strlen($slug) <= 8) {
                        $slug = '_' . $slug;
                    }
                    return $slug;
                },
            ]
        ];
    }
}
