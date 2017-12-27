<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories';

    protected $fillable = ['name','description','post_count'];

    public function topics()
    {
        return $this->hasMany(Topic::class,'category_id','id');
    }

}
