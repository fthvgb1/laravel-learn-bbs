<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\AuthorizationRequest;
use App\Http\Requests\Api\SocialAuthorizationRequest;
use App\Models\User;
use Laravel\Socialite\Facades\Socialite;

class AuthorizationsController extends Controller
{
    /**
     * @param $type
     * @param SocialAuthorizationRequest $request
     * @throws \ErrorException
     */
    public function socialStore($type, SocialAuthorizationRequest $request)
    {
        if (!in_array($type, ['weixin'])) {
            return $this->response->errorBadRequest();
        }
        $driver = Socialite::driver($type);
        try {
            if ($code = $request->get('code')) {
                $response = $driver->getAccessTokenResponse($code);
                $token = array_get($response, 'access_token');
            } else {
                $token = $request->get('access_token');
                if ($type == 'weixin') {
                    $driver->setOpenId($request->get('openid'));
                }
            }
            $oauthUser = $driver->userFromToken($token);

        } catch (\Exception $exception) {
            return $this->response->errorUnauthorized('参数错误,未获取用户信息');
        }
        switch ($type) {
            case 'weixin':
                $unionid = $oauthUser->offsetExists('unionid') ? $oauthUser->offsetGet('unionid') : null;

                $user = $unionid ? User::where('weixin_unionid', $unionid)->first() : User::where('weixin_openid', $oauthUser->getId())->first();
                if (!$user) {
                    $user = User::create([
                        'name' => $oauthUser->getNickname(),
                        'avatar' => $oauthUser->getAvatar(),
                        'weixin_openid' => $oauthUser->getId(),
                        'weixin_unionid' => $unionid,
                    ]);
                }
                break;
        }

        $token = \Auth::guard('api')->fromUser($user);

        return $this->respondWithToken($token)->setStatusCode(201);
    }

    /**
     * @param AuthorizationRequest $request
     * @throws \ErrorException
     */
    public function store(AuthorizationRequest $request)
    {
        $username = $request->get('username');
        filter_var($username, FILTER_VALIDATE_EMAIL) ? $credentials['email'] = $username : $credentials['phone'] = $username;
        $credentials['password'] = $request->get('password');
        if (!$token = \Auth::guard('api')->attempt($credentials)) {
            return $this->response->errorUnauthorized(trans('auth.failed'));
        }
        return $this->respondWithToken($token)->setStatusCode(201);
    }

    /**
     * @return mixed
     * @throws \ErrorException
     */
    public function update()
    {
        $token = \Auth::guard('api')->refresh();
        return $this->respondWithToken($token);
    }

    /**
     * @return \Dingo\Api\Http\Response
     */
    public function destroy()
    {
        \Auth::guard('api')->logout();
        return $this->response->noContent();
    }


    /**
     * @param $token
     * @return mixed
     * @throws \ErrorException
     */
    protected function respondWithToken($token)
    {
        return $this->response->array([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => \Auth::guard('api')->factory()->getTTL() * 60
        ]);
    }
}
