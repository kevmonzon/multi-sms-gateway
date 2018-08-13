<?php

namespace App\Repositories\MultiSms;

use App\Models\Sms;

class MultiSms {

    const DIRECTION_OUTBOUND = 'OUTBOUND';
    const DIRECTION_INBOUND = 'INBOUND';

    private $to;
    private $from;
    private $message;
    private $gateway;
    private $id;


    public function __construct()
    {
        
    }

    public function to($to)
    {
        $this->to = $to;
        return $this;
    }

    public function from($from)
    {
        $this->from = $from;
        return $this;
    }

    public function message($message)
    {
        $this->message = $message;
        return $this;
    }

    public function gateway($gateway)
    {
        $this->gateway = $gateway;
        return $this;
    }

    public function send()
    {
        // validation
        // check if from is blacklisted

        // add log
        $this->addLog();
        
        try {
            // call class
            $driver = "App\\Repositories\\MultiSms\\drivers\\" . $this->gateway;
            $driverInst = new $driver($this->id);
            $driverInst = $driver->to($this->to)
                            ->from($this->from)
                            ->message($this->message)
                            ->send();

            return $driverInst;

        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function inbound()
    {
        // parse inbound msg first

        // add log 
        $this->addLog();
        // class class 
        $driver = "App\\Repositories\\MultiSms\\drivers\\" . $this->gateway;
        
    }

    public function status()
    {
        $sms = null;
        // check current status of this sms
        if ($this->sms_id) {
            $sms = Sms::where('id', $this->sms_id)->latestStatus();
        }

        return $sms;
    }

    public function addLog($direction)
    {
        $sms = new Sms;
        $sms->to = $this->to;
        $sms->from = $this->from;
        $sms->message = $this->message;
        $sms->direction = $direction;
        $sms->save();

        $this->id = $sms->id;

        return $sms;
    }
}