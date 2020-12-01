<?php

namespace Main\Permission\Model;

use Illuminate\Database\Eloquent\Model;
use Main\App\Exceptions\NotFoundException;
use Main\Role\Model\Role;

class Permission extends Model
{
    protected $table = 'permissions';
    protected $fillable = ['title', 'class'];

    protected $hidden = ['pivot', 'class'];

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

    public function role()
    {
        return $this->belongsToMany(Role::class,
            'permission_role', 'permission', 'role');
    }

    public static function hasClass($class)
    {
        return (bool) self::query()->firstWhere('class' , $class);
    }

}
