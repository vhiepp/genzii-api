<?php

namespace App\Services;

use App\Models\Avatar;
use App\Models\User;
use App\Services\Notifications\NotificationMessage;
use App\Services\Notifications\NotificationService;
use App\Services\Notifications\NotificationType;

class UserService
{
    public function sendFriendRequest(string|User $userRequest = null, string|User $userIsRequested = null)
    {
        if (gettype($userRequest) == 'string') {
            $userRequest = User::find($userRequest);
        }
        if (gettype($userIsRequested) == 'string') {
            $userIsRequested = User::find($userIsRequested);
        }
        if ($userRequest && $userIsRequested && ($userRequest->id != $userIsRequested->id)) {
            $userIsRequested->sendFriendRequests()->updateExistingPivot($userRequest->id, ['status' => 'cancelled']);
            $userRequest->sendFriendRequests()->syncWithoutDetaching([$userIsRequested->id => ['status' => 'await']]);
            $notificationService = new NotificationService();
            $notificationService->createNew(
                NotificationType::NEW_FRIEND_REQUEST,
                $userIsRequested,
                $userRequest,
                NotificationMessage::NEW_FRIEND_REQUEST
            );
            return true;
        }
        return false;
    }

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
            $notificationService = new NotificationService();
            $notificationService->createNew(
                NotificationType::AGREED_FRIEND_REQUEST,
                $userTwo,
                $userOne,
                NotificationMessage::AGREED_FRIEND_REQUEST
            );
            $userOne->friendRequests()->updateExistingPivot($userTwo->id, ['status' => 'agreed']);
            $userTwo->friendRequests()->updateExistingPivot($userOne->id, ['status' => 'agreed']);
            return true;
        }
        return false;
    }

    public function cancelledFriendRequests(string|User $userRequest = null, string|User $userIsRequested = null)
    {
        if (gettype($userRequest) == 'string') {
            $userRequest = User::find($userRequest);
        }
        if (gettype($userIsRequested) == 'string') {
            $userIsRequested = User::find($userIsRequested);
        }
        if ($userRequest && $userIsRequested && ($userRequest->id != $userIsRequested->id)) {
            $userIsRequested->friendRequests()->updateExistingPivot($userRequest->id, ['status' => 'cancelled']);
            return true;
        }
        return false;
    }

    public function cancelledFriend(string|User $userOne = null, string|User $userTwo = null)
    {
        if (gettype($userOne) == 'string') {
            $userOne = User::find($userOne);
        }
        if (gettype($userTwo) == 'string') {
            $userTwo = User::find($userTwo);
        }
        if ($userOne && $userTwo && ($userOne->id != $userTwo->id)) {
            $userOne->friends()->syncWithoutDetaching([$userTwo->id => ['status' => 'unfriended']]);
            $userTwo->friends()->syncWithoutDetaching([$userOne->id => ['status' => 'unfriended']]);
            return true;
        }
        return false;
    }

    public function isFollowingUser(string|User $user = null, string|User $userIsFollowed = null): bool
    {
        if (gettype($user) == 'string') {
            $user = User::find($user);
        }
        if (gettype($userIsFollowed) == 'string') {
            $userIsFollowed = User::find($userIsFollowed);
        }
        if ($user && $userIsFollowed && ($user->id != $userIsFollowed->id)) {
            if ($user->following()->where('id', $userIsFollowed->id)->first()) {
                return true;
            }
        }
        return false;
    }

    public function changeAvatar(string|User $user, string|Avatar $avatar)
    {
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
