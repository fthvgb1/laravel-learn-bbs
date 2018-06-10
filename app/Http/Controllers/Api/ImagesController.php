<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\ImageRequest;
use App\Models\Image;
use App\Tools\ImageUploadTool;
use App\Transformers\ImageTransformer;

class ImagesController extends Controller
{
    public function store(ImageRequest $request, ImageUploadTool $uploadTool, Image $image)
    {
        $user = $this->user();
        $size = $request->get('type') == 'avatar' ? 362 : 1024;
        $result = $uploadTool->save($request->image, str_plural($request['type']), $user->id, $size);
        $image->path = $result['path'];
        $image->type = $request['type'];
        $image->user_id = $user->id;
        $image->save();
        return $this->response->item($image, new ImageTransformer())->setStatusCode(201);
    }
}
