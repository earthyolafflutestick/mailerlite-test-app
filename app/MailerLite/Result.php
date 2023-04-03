<?php

namespace App\MailerLite;

class Result
{
    public $message;
    public $data;
    public $meta;

    public function __construct($message, $data, $meta = null)
    {
        $this->message = $message;
        $this->data = $data;
        $this->meta = $meta;
    }
}
