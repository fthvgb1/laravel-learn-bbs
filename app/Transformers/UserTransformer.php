<?php

namespace App\TransFormers;


use App\Models\User;
use Carbon\Carbon;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{
    protected $availableIncludes = ['roles'];

    public function transform(User $user)
    {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'avatar' => $user->avatar,
            'introduction' => $user->introduction,
            'bound_phone' => $user->phone ? true : false,
            'bound_wechat' => ($user->weixin_unionid || $user->weixin_openid) ? true : false,
            'last_actived_at' => $user->last_actived_at instanceof Carbon ? $user->last_actived_at->toDateTimeString() : $user->last_actived_at,
            'created_at' => $user->created_at instanceof Carbon ? $user->created_at->toDateTimeString() : $user->created_at,
            'updated_at' => $user->updated_at instanceof Carbon ? $user->updated_at->toDateTimeString() : $user->updated_at,
        ];
    }

    public function includeRoles(User $user)
    {
        return $this->collection($user->roles, new RoleTransformer());
    }
}