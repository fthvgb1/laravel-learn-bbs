<?php
/**
 * Created by PhpStorm.
 * User: xing
 * Date: 2018/6/16
 * Time: 8:25
 */

namespace App\Transformers;


use App\Models\Link;
use League\Fractal\TransformerAbstract;

class LinkTransformer extends TransformerAbstract
{
    public function transform(Link $link)
    {
        return [
            'id' => $link->id,
            'title' => $link->title,
            'link' => $link->link
        ];
    }
}