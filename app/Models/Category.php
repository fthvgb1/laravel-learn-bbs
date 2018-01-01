<?php

namespace App\Models;

class Category extends BaseModel
{
    protected $guarded = ['id'];


    public function topics()
    {
        return $this->hasMany(Topic::class);
    }
}
