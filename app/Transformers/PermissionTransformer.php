<?php
/**
 * Created by PhpStorm.
 * User: xing
 * Date: 2018/6/11
 * Time: 22:35
 */

namespace App\Transformers;


use League\Fractal\TransformerAbstract;
use Spatie\Permission\Models\Permission;

class PermissionTransformer extends TransformerAbstract
{
    public function transform(Permission $permission)
    {
        return [
            'id' => $permission->id,
            'name' => $permission->name,
        ];
    }
}