<?php

namespace Main\Language\Model;

use Illuminate\Database\Eloquent\Model;
use Main\App\Exceptions\NotFoundException;

class Language extends Model
{
    protected $table = 'languages';
    protected $fillable = ['lang', 'primary'];

    protected $hidden = ['pivot'];

    public $timestamps = false;

    public function getRouteKeyName()
    {
        return 'lang';
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
