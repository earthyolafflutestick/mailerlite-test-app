<?php

namespace App\MailerLite;

class Subscriber
{
    public $id;
    public $firstName;
    public $lastName;
    public $country;
    public $email;
    public $subscribeDate;
    public $subscribeTime;

    public function __construct($id, $email, $firstName, $lastName, $country, $subscribe_date, $subscribe_time)
    {
        $this->id = $id;
        $this->email = $email;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->country = $country;
        $this->subscribeDate = $subscribe_date;
        $this->subscribeTime = $subscribe_time;
    }
}
