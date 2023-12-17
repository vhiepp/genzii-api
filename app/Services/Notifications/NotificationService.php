<?php

namespace App\Services\Notifications;

use App\Models\User;

class NotificationService
{
    public function createNew(
        string $notificationType = NotificationType::LIKE_POST,
        string|User $userIsNotified = null,
        string|User $userCreatedNotification = null,
        string $message = '',
        $detailData = null
    )
    {
        if (gettype($userIsNotified) == 'string') {
            $userIsNotified = User::find($userIsNotified);
        }
        if (gettype($userCreatedNotification) == 'string') {
            $userCreatedNotification = User::find($userCreatedNotification);
        }
        if (
            $userIsNotified &&
            $userCreatedNotification &&
            ($userIsNotified->id != $userCreatedNotification->id) &&
            $notificationType
        ) {
            if ($notificationType == NotificationType::NEW_FRIEND_REQUEST) {
                $userIsNotified->notifications()->updateOrCreate([
                    'type' => $notificationType
                ], [
                    'type' => $notificationType,
                    'done_by_user_id' => $userCreatedNotification->id,
                    'message' => $message,
                    'detail_data' => json_encode($detailData),
                    'status' => 'not_seen'
                ]);
            } else {
                $userIsNotified->notifications()->create([
                    'done_by_user_id' => $userCreatedNotification->id,
                    'type' => $notificationType,
                    'message' => $message,
                    'detail_data' => json_encode($detailData),
                ]);
            }
            return true;
        }
        return false;
    }

}
