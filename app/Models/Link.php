<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

/**
 * Class Link
 * @property int id
 * @property string title
 * @property string link
 * @package App\Models
 */
class Link extends Model
{
    public $cache_key = 'larabbs_key';
    public $cache_expire_in_minutes = 1440;
    protected $fillable = ['title', 'link'];

    public function getAllCached()
    {
        return Cache::remember($this->cache_key, $this->cache_expire_in_minutes, function () {
            {
                return $this->all();
            }
        });
    }
}
