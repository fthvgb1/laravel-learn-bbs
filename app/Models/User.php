<?php

namespace App\Models;

use App\Traits\ActiveUserHelper;
use App\Traits\LastActivedAtHelper;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Traits\HasRoles;

/**
 * Class User
 * @property string avatar
 * @package App\Models
 */
class User extends Authenticatable
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
        'name', 'email', 'password', 'introduction', 'avatar '
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * @return string
     */
    public function getHeaderAttribute()
    {
        return asset($this->avatar);
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
