<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\UserRequest;
use App\Models\Image;
use App\Models\User;
use App\TransFormers\UserTransformer;
use Illuminate\Support\Facades\Cache;

class UsersController extends Controller
{
    public function store(UserRequest $request)
    {
        $verifyData = Cache::get($request->get('verification_key'), '');
        if (!$verifyData) {
            return $this->response->error('验证码失效', 422);
        }
        if (!hash_equals($verifyData['code'], $request->get('verification_code'))) {
            return $this->response->errorUnauthorized('验证码错误');
        }
        $user = User::create($request->all(['name', 'phone', 'password']));
        // 清除验证码缓存
        \Cache::forget($request->verification_key);

        return $this->response->item($user, new UserTransformer())
            ->setMeta([
                'access_token' => \Auth::guard('api')->fromUser($user),
                'token_type' => 'Bearer',
                'expires_in' => \Auth::guard('api')->factory()->getTTL() * 60
            ])
            ->setStatusCode(201);
    }

    public function update(UserRequest $request)
    {
        $user = $this->user();
        $attributes = $request->only(['name', 'email', 'introduction']);
        if ($request->get('avatar_image_id')) {
            $image = Image::find($request['avatar_image_id']);
            $attributes['avatar'] = $image->path;
        }
        $user->update($attributes);
        return $this->response->item($user, new UserTransformer());
    }

    public function me()
    {
        return $this->response->item($this->user(), new UserTransformer());
    }
}
