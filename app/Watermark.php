<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Watermark extends Model
{
    protected $fillable = [
        'name', 'origin'
    ];
}
