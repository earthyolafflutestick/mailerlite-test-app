<?php

namespace App\MailerLite;

class Error
{
    public $status;
    public $message;
    public $errors;

    public function __construct($status, $message, $errors = [])
    {
        $this->status = $status;
        $this->message = $message;
        $this->errors = $errors;
    }
}
