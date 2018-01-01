<?php

namespace App\Models;

class Category extends Model
{
    protected $guarded = ['id'];


    public function topics()
    {
        return $this->hasMany(Topic::class);
    }
}
