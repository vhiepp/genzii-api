<?php

namespace App\Services;

use App\Models\Avatar;
use App\Models\User;

class UserService
{
    public function addFriend(string|User $userOne = null, string|User $userTwo = null)
    {
        if (gettype($userOne) == 'string') {
            $userOne = User::find($userOne);
        }
        if (gettype($userTwo) == 'string') {
            $userTwo = User::find($userTwo);
        }
        if ($userOne && $userTwo && ($userOne->id != $userTwo->id)) {
            $userOne->friends()->syncWithoutDetaching([$userTwo->id => ['status' => 'friend']]);
            $userTwo->friends()->syncWithoutDetaching([$userOne->id => ['status' => 'friend']]);

            $userOne->friendRequests()->updateExistingPivot($userTwo->id, ['status' => 'agreed']);
            $userTwo->friendRequests()->updateExistingPivot($userOne->id, ['status' => 'agreed']);

            return true;
        }
        return false;
    }

    public function changeAvatar(string|User $user, string|Avatar $avatar) {
        try {
            if (gettype($user) == 'string') {
                $user = User::find($user);
            }
            $user->avatars()->where('current', true)->update(['current' => false]);
            if (gettype($avatar) == 'string') {
                if (str()->isUuid($avatar)) {
                    $avatar = $user->avatars()->find($avatar);
                } else {
                    $user->avatars()->create(['url' => $avatar]);
                    return true;
                }
            }
            $user->avatars()->find($avatar->id)->update(['current' => true]);
            return true;
        } catch (\Exception $ex) {
            return false;
        }
    }
}
