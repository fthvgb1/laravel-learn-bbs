<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\UserRequest;
use App\Models\User;
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

        return $this->response->created();
    }
}
