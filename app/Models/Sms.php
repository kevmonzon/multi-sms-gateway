<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// this is the main table for sms
class Sms extends Model
{
    protected $table = 'sms';

    const DIRECTION_INBOUND = 'INBOUND';
    const DIRECTION_OUTBOUND = 'OUTBOUND';

    public function logs()
    {
        return $this->hasMany(SmsLog::class);
    }

    public function lastUpdate()
    {
        return $this->hasMany(SmsLog::class)->orderBy('created_at', 'DESC')->first();
    }

    public function latestStatus()
    {
        return $this->hasMany(SmsLog::class)->orderBy('created_at', 'DESC')->first()->status;
    }
}
