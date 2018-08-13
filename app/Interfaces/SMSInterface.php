<?php namespace App\Interfaces;


/**
 * This interface will define the MODEL of the SMS entry
 * and which data you can get from this record
 * 
 * this should be read only i guess
 */


interface SMSInterface
{
    public function to($number);

    public function from($number);

    public function message($message);

    public function send();

    public function status($db_id);

}