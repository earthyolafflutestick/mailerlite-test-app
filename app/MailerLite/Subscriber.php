<?php

namespace App\MailerLite;

class Subscriber
{
    public $id;
    public $name;
    public $country;
    public $email;
    public $subscribeDate;
    public $subscribeTime;

    public function __construct($id, $email, $name, $country, $subscribe_date, $subscribe_time)
    {
        $this->id = $id;
        $this->email = $email;
        $this->name = $name;
        $this->country = $country;
        $this->subscribeDate = $subscribe_date;
        $this->subscribeTime = $subscribe_time;
    }
}
