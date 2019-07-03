<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Slack extends Model
{
    use LogsActivity;

    protected $fillable = [
        'user_id', 'cw', 'token', 'checkin', 'checkout', 'channel'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
