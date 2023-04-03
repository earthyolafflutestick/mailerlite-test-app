<?php

namespace App\MailerLite;

class Subscriber
{
    public $id;
    public $firstName;
    public $lastName;
    public $country;
    public $email;

    public function __construct($id, $email, $firstName, $lastName, $country)
    {
        $this->id = $id;
        $this->email = $email;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->country = $country;
    }
}
