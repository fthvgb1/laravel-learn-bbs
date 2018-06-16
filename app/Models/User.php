<?php

namespace App\Models;

use App\Traits\ActiveUserHelper;
use App\Traits\LastActivedAtHelper;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * Class User
 * @property string avatar
 * @property string name
 * @property string email
 * @property string introduction
 * @property string phone
 * @property string weixin_unionid
 * @property string weixin_openid
 * @property Role roles
 * @package App\Models
 */
class User extends Authenticatable implements JWTSubject
{
    use Notifiable {
        notify as protected laravelNotify;
    }
    use HasRoles;
    use ActiveUserHelper;
    use LastActivedAtHelper;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'phone', 'email', 'password', 'introduction', 'avatar ',
        'weixin_openid', 'weixin_unionid', 'registration_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * @return string
     */
    public function getHeaderAttribute()
    {
        $hash = md5(strtolower(trim($this->attributes['email'])));
        $header = "http://www.gravatar.com/avatar/$hash";
        return $this->avatar ? asset($this->avatar) : $header;
    }

    public function getLastActiveATAttribute($value)
    {
        return $this->getLastActivedAt($value);
    }


    /**
     * @param $path
     */
    public function setAvatarAttribute($path)
    {
        if (!starts_with($path, 'http')) {
            $path = config('app.url') . "/storage/app/public/avatar/{$path}";
        }
        $this->attributes['avatar'] = $path;
    }

    /**
     * @param $model
     * @return bool
     */
    public function isAuthorOf($model)
    {
        return $this->id == $model->user_id;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function replies()
    {
        return $this->hasMany(Reply::class, 'user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function topics()
    {
        return $this->hasMany(Topic::class);
    }

    /**
     * @param $value
     */
    public function setPasswordAttribute($value)
    {
        if (!isset($value[59])) {
            $value = bcrypt($value);
        }
        $this->attributes['password'] = $value;
    }

    /**
     * @param $instance
     */
    public function notify($instance)
    {
        if ($this->id == Auth::id()) {
            return;
        }
        $this->increment('notification_count');
        $this->laravelNotify($instance);
    }

    public function markAsRead()
    {
        $this->notification_count = 0;
        $this->save();
        $this->unreadNotifications->markAsRead();
    }
}
