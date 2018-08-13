<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// this contains logs 
class SmsLog extends Model
{
    protected $table = 'sms_logs';

    const STATUS_QUEUED = 'QUEUED';
    const STATUS_SENDING = 'SENDING';
    const STATUS_SENT = 'SENT';
    const STATUS_FAILED = 'FAILED';
    const STATUS_DELIVERED = 'DELIVERED';
    const STATUS_UNDELIVERED = 'UNDELIVERED';
    const STATUS_RECEIVED = 'RECEIVED';
    const STATUS_UNKNOWN = 'UNKNOWN';

    function sms() 
    {
        return $this->belongsTo(Sms::class);
    }
}
