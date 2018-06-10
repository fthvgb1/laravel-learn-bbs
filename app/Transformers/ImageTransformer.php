<?php
/**
 * Created by PhpStorm.
 * User: xing
 * Date: 2018/6/10
 * Time: 16:47
 */

namespace App\Transformers;


use App\Models\Image;
use Carbon\Carbon;
use League\Fractal\TransformerAbstract;

class ImageTransformer extends TransformerAbstract
{
    public function transform(Image $image)
    {
        return [
            'id' => $image->id,
            'user_id' => $image->user_id,
            'type' => $image->type,
            'path' => $image->path,
            'created_at' => $image->created_at instanceof Carbon ? $image->created_at->toDateTimeString() : $image->created_at,
            'updated_at' => $image->updated_at instanceof Carbon ? $image->updated_at->toDateTimeString() : $image->updated_at,
        ];
    }
}