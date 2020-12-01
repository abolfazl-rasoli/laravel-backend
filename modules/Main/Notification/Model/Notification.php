<?php

namespace Main\Notification\Model;

use Illuminate\Database\Eloquent\Model;
use Main\App\Exceptions\NotFoundException;

class Notification extends Model
{
    protected $table = 'notifications';
    protected $fillable = ['title' , 'user_id', 'type' , 'link', 'view'];

    protected $hidden = ['pivot'];

    public $timestamps = false;

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
