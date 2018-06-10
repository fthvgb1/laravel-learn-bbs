<?php

namespace App\Models;

/**
 * Class Category
 * @property int $id
 * @property string $name
 * @property string $description
 * @package App\Models
 */
class Category extends Model
{
    protected $guarded = ['id'];


    public function topics()
    {
        return $this->hasMany(Topic::class);
    }
}
