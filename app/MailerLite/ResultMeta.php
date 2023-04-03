<?php

namespace App\MailerLite;

class ResultMeta
{
    public $prevCursor;
    public $nextCursor;

    public function __construct($prevCursor, $nextCursor)
    {
        $this->prevCursor = $prevCursor;
        $this->nextCursor = $nextCursor;
    }
}
