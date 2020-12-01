<?php


namespace Main\Notification\Traits;


use Main\Notification\Http\Resources\NotificationResource;
use Main\Notification\Model\NotificationTypes;

trait NotificationTraits
{

    public function getMe()
    {

        $my = auth()->user();
        $webNotify = $my->getNotifications()
            ->whereIn('type' , NotificationTypes::TYPE_WEB)->paginate(env('PAGINATE'));
        $resourceNotify = new NotificationResource($webNotify);

        $my->getNotifications()->where('type' , 3)->update(['view' => 1]);

        return $resourceNotify;

    }

    public function seenNotification($notification)
    {

        if($notification->user_id === auth()->id()){
            $notification->update(['view' => 1]);
        }

        return $this->getMe();

    }

}
