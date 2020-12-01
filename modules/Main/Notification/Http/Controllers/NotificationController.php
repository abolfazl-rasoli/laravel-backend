<?php

namespace Main\Notification\Http\Controllers;
use App\Http\Controllers\Controller;
use Main\App\Helper\TryCache;
use Main\Notification\Http\Requests\MeNotificationRequest;
use Main\Notification\Http\Resources\NotificationResource;
use Main\Notification\Traits\NotificationTraits;

class NotificationController extends Controller
{

    use NotificationTraits;

    /** get my notifications
     * @param MeNotificationRequest $request
     * @return mixed
     */
    public function me(MeNotificationRequest $request)
    {
        return TryCache::render(function (){

            return $this->getMe();

        });
    }

    /** seen my notification
     * @param MeNotificationRequest $request
     * @return mixed
     */
    public function seen(MeNotificationRequest $request)
    {
        return TryCache::render(function () use ($request) {

            return $this->seenNotification($request->notification);

        });
    }

}
