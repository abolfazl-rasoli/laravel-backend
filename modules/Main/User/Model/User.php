<?php


namespace Main\User\Model;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\HasApiTokens;
use Main\App\Exceptions\NotFoundException;
use Main\App\Helper\Helper;
use Main\Notification\Model\Notification;
use Main\Role\Model\Role;
use Main\Uploader\Uploader;
use Main\User\Broadcasting\EmailChannel;
use Main\User\Exceptions\InactiveException;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'role', 'mobile', 'verified_at', 'deleted_at', 'avatar'
    ];

    protected $appends = ['role_name'];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'deleted_at', 'pivot'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'verified_at' => 'datetime',
    ];

    public static $login = ['email' => '/^\S+@\S+\.\S+$/', 'mobile' => '/^09[0-9]{9}$/'];

    public static $loginNotify = ['email' => EmailChannel::class];

    public static $searchQuery = ['name', 'email', 'mobile'];

    public function findForPassport($username)
    {

        $user = $this->withTrashed()->where(Helper::fieldEmailOrMobile($username), $username)->first();
        if ($user) {
            if ($user->deleted_at) {
                throw new InactiveException(trans('messages.loginInactiveUser'), 401);
            }
            return ($user->verified_at) ? $user : null;
        }
        return null;
    }

    /**
     *  Override password check here
     * @param $password
     * @return mixed
     */
    public function validateForPassportPasswordGrant($password)
    {
        if(Cache::has($password)){

            Cache::forget($password);
            return true;
        }

        return Hash::check($password, $this->password);
    }

    public function getAvatarAttribute($value)
    {
        return $value ? Uploader::getUrl($value, 'avatar') : null;
    }

    public function getRoleNameAttribute()
    {
        return $this->role ? Role::withTrashed()->find($this->role)->title : $this->role;
    }

    public function getRouteKeyName()
    {
        return 'id';
    }


    public function resolveRouteBinding($value, $field = null)
    {
        $user = $this->where($field ?? $this->getRouteKeyName(), $value)->first();
        if ($user) {
            return $user;
        }
        throw new NotFoundException(trans('messages.notfoundUser'));
    }

    public function getRole()
    {
        return $this->belongsTo(Role::class, 'role', 'id')->withTrashed();
    }

    public function from()
    {
        return $this->belongsToMany(static::class,
            'follows', 'to', 'from');
    }

    public function to()
    {
        return $this->belongsToMany(static::class,
            'follows', 'from', 'to');
    }

    public function isSuperAdmin()
    {
        return $this->getRole && $this->getRole->title === 'super_admin';
    }

    public function checkPermission($permission)
    {
        $role = $this->getRole;
        if (!$role) return false;

        return $role->permission->firstWhere('class', $permission);
    }


    public function getNotifications()
    {
        return $this->hasMany(Notification::class, 'user_id', 'id');
    }


}
