<?php

namespace App\Contracts;

use App\DTOs\OtpMessageData;

interface OtpSender
{
    public function send(OtpMessageData $message): void;
}
