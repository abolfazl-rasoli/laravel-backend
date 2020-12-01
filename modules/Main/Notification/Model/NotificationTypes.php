<?php

namespace Main\Notification\Model;

use Illuminate\Database\Eloquent\Model;
use Main\App\Exceptions\NotFoundException;

class NotificationTypes extends Model
{
    protected $table = 'notification_types';
    protected $fillable = ['key' ];

    protected $hidden = [];
    public $timestamps = false;

    const TYPE_PHONE = 'type_phone';
    const TYPE_EMAIL = 'type_email';
    const TYPE_WEB_WITHOUT_LINK = 'type_web';
    const TYPE_WEB_WITH_LINK = 'type_web_with_link';

    const TYPES = [self::TYPE_PHONE , self::TYPE_EMAIL , self::TYPE_WEB_WITHOUT_LINK , self::TYPE_WEB_WITH_LINK];
    const TYPE_WEB = [3, 4];

    public function getRouteKeyName()
    {
        return 'id';
    }

    public function resolveRouteBinding($value, $field = null)
    {
        $find = $this->where($this->getRouteKeyName(), $value)->first();
        if ($find){
            return $find;
        }
        throw new NotFoundException(trans('messages.notfound'));
    }

}
