<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Poll extends Model
{
    protected $fillable = [
        'content', 'creator', 'message', 'status', 'room'
    ];
}
