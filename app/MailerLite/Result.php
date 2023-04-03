<?php

namespace App\MailerLite;

class Result
{
    public $message;
    public $data;

    public function __construct($message, $data)
    {
        $this->message = $message;
        $this->data = $data;
    }
}
