<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Image
 * @property int id
 * @property int user_id
 * @property string created_at
 * @property string updated_at
 * @property string type
 * @property string path
 * @package App\Models
 */
class Image extends Model
{
    protected $fillable = ['type', 'path'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
