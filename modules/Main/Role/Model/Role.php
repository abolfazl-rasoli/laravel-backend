<?php

namespace Main\Role\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Main\App\Exceptions\NotFoundException;
use Main\Language\Translates\Translates;
use Main\Permission\Model\Permission;

class Role extends Model
{
    use SoftDeletes;

    protected $table = 'roles';
    protected $fillable = ['title'];

    protected $hidden = ['pivot'];

    protected $appends = ['title_tr'];

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

    public function permission()
    {
        return $this->belongsToMany(Permission::class,
            'permission_role', 'role', 'permission');
    }

    public function getTitleTrAttribute()
    {
        return Translates::trans($this->title);
    }
}
