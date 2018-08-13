<?php

namespace App\Repositories\MultiSms\drivers;

use App\Models\Sms;
use App\Models\SmsLog;
use Twilio\Rest\Client;
use Twilio\Exceptions\TwilioException;

class Twilio {

    private $to;
    private $from;
    private $message;
    private $sms_id;
    private $api_id;

    private $gateway;
    private $sid;
    private $token;

    public function __construct($sms_id)
    {
        try {
            if (!config('multisms.twilio.sid') OR !config('multisms.twilio.token')){
                // if not set, then log failed attempt
                $this->addLog(SmsLog::STATUS_FAILED, [], "API credentials not set!");
                throw new \Exception("[MULTI SMS] Twilio API Credentials not set!");
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
        $this->sid = config('multisms.twilio.sid');
        $this->token = config('multisms.twilio.token');
        $this->sms_id = $sms_id;
        $this->gateway = new Client($this->sid, $this->token);

    }

    public function to($to) {
        $this->to = $to;
        return $this;
    }

    public function from($from) {
        $this->from = $from;
        return $this;
    }

    public function message($message) {
        $this->message = $message;
        return $this;
    }

    public function send() {
        try {
            // check FROM number is from this gateway
            

            // set default FROM number
            if (!$this->from) {
                $this->from = 'DEFAULT NUMBER'; // @todo
            }

            // prepare data to be sent to twilio
            $twilioData = [
                'from' => $this->from,
                'body' => $this->message,
                'statusCallback' => route('sms_status', [
                    'gateway' => strtolower(get_class($this))
                ])
            ];

            // send to twilio
            $twilioRequest = $this->gateway->messages->create(
                $this->to,
                $twilioData
            );

            // process response / errors
            $addData = '';
            if ($twilioRequest->errorCode) {
                $addData = $twilioRequest->errorCode . " : " . $twilioRequest->errorMessage;
            }

            // add log
            $this->api_id = $twilioRequest->sid;
            $this->addLog($twilioRequest->status, $twilioRequest->toArray(), $addData);
        
            // return data
            return $twilioRequest;
        } catch (TwilioException $e) {
            return $e->getMessage();
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    // inbound
    public function inbound() {

    }

    public function addLog($status, $api_response = [], $logs = null)
    {
        $sms = new SmsLog;
        $sms->sms_id = $this->sms_id;
        $sms->gateway = strtoupper(get_class($this));
        $sms->api_id = $this->api_id;
        $sms->status = $this->parseLog($status);
        $sms->api_response = json_encode($api_response);
        $sms->logs = $logs;
        $sms->save();

        $this->id = $sms->id;
    }

    public function parseLog($status)
    {
        switch ($status){
            case 'queued':
                return SmsLog::STATUS_QUEUED;
            case 'sending':
                return SmsLog::STATUS_SENDING;
            case 'sent':
                return SmsLog::STATUS_SENT;
            case 'failed':
                return SmsLog::STATUS_FAILED;
            case 'delivered':
                return SmsLog::STATUS_DELIVERED;
            case 'undelivered':
                return SmsLog::STATUS_UNDELIVERED;
            case 'received';
                return SmsLog::STATUS_RECEIVED;
            default:
                return 'UNKNOWN';

        }
    }
}