<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Zan extends Model
{
    protected $table = 'zans';

    protected $fillable = ['user_id','topic_id'];
}
