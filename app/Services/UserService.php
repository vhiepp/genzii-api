<?php

namespace App\Services;

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
}
