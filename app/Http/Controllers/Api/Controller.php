<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller as BaseController;
use Dingo\Api\Routing\Helpers;
use Symfony\Component\HttpKernel\Exception\HttpException;

class Controller extends BaseController
{
    use Helpers;


    public function errorResponse($statusCode, $message = '', $code = 0)
    {
        throw new HttpException($statusCode, $message, null, [], $code);
    }
}
